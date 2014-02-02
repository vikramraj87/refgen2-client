<?php
namespace Api\Service;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\EventManager\EventManager;


class ApiService
{
    /** @var Client */
    protected $client = null;

    /** @var string */
    protected $host = '';

    /** @var Container */
    protected $container = null;

    /** @var array */
    protected $paths = array(
        'pubmed_search'     => 'pubmed/term/:term/:page',
        'pubmed_single'     => 'pubmed/id/:id',
        'collection'        => 'collection/user/:user_id/:id',
        'collection_user'   => 'collection/user/:user_id',
    );

    /** @var EventManager */
    protected $events = null;

    /**
     * @return EventManager
     */
    public function events()
    {
        if($this->events == null) {
            $this->events = new EventManager(__CLASS__);
        }
        return $this->events;
    }

    public function getArticle($id)
    {
        $result = $this->searchCache($id);
        if(is_array($result) && isset($result['count']) &&
            is_array($result['results']) && count($result['results']) == 1)
        {
            return $result;
        }
        $vars = array(
            ':id' => $id
        );
        $path = strtr($this->paths['pubmed_single'], $vars);
        $result = $this->doRequest($path);
        if(!isset($result['count'])) {
            throw new \RuntimeException('Invalid response from the api');
        }
        return $result;
    }

    public function search($term, $page = 1)
    {
        $page = abs((int) $page);

        $result = $this->getFromCache($term, $page);

        if(is_array($result) && isset($result['count']) &&
            is_array($result['results']) && !empty($result['results']))
        {
            return $result;
        }

        $vars = array(
            ':term' => urlencode($term),
            ':page' => $page
        );
        $path = strtr($this->paths['pubmed_search'], $vars);
        $result = $this->doRequest($path);
        if(!isset($result['count'])) {
            throw new \RuntimeException('Invalid response from the api');
        }
        if($result['count']) {
            $this->cacheResults($result, $term, $page);
        }
        return $result;
    }

    public function getCollection($id, $userId)
    {
        $vars = array(
            ':id' => (int) $id,
            ':user_id' => (int) $userId
        );
        $path = strtr($this->paths['collection'], $vars);
        return $this->doRequest($path);
    }

    public function getCollections($userId)
    {
        $vars = array(
            ':user_id' => (int) $userId
        );
        $path = strtr($this->paths['collection_user'], $vars);
        return $this->doRequest($path);
    }

    public function createCollection($userId, $data)
    {
        $vars = array(
            ':user_id' => (int) $userId
        );
        $path = strtr($this->paths['collection_user'], $vars);
        return $this->doRequest($path, $data, Request::METHOD_POST);
    }

    public function updateCollection($id, $userId, $data)
    {
        $vars = array(
            ':user_id' => (int) $userId,
            ':id'      => (int) $id
        );
        $path = strtr($this->paths['collection'], $vars);
        return $this->doRequest($path, $data, Request::METHOD_PUT);
    }

    public function deleteCollection($id, $userId)
    {
        $vars = array(
            ':user_id' => (int) $userId,
            ':id'      => (int) $id
        );
        $path = strtr($this->paths['collection'], $vars);
        return $this->doRequest($path, array(), Request::METHOD_DELETE);
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = rtrim($host, '/');
        return $this;
    }

    /**
     * @param Container $container
     * @return $this
     */
    public function setCacheContainer(Container $container)
    {
        $this->container = $container;
        return $this;
    }

    protected function doRequest($path, $data = array(), $method = Request::METHOD_GET)
    {
        if($this->client === null) {
            throw new \RuntimeException('Client not set in Api Service');
        }
        if($this->host === '') {
            throw new \RuntimeException('Host not set in Api Service');
        }

        $url = $this->host . '/' . ltrim($path, '/');

        /* For debugging
        $debug = array(
            'url' => $url,
            'data' => $data,
            'method' => $method
        );
        return $debug;
        /* End of debugging */

        $client = $this->client;
        $client->setUri($url);
        $client->setMethod($method);

        if(!empty($data)) {
            $client->setParameterPost($data);
        }
        $this->events()->trigger(
            'dispatch.pre',
            $this,
            array(
                'url' => $url,
                'data' => $data,
                'method' => $method
            )
        );
        /** @var Response $response */
        $response = $client->send();

        if($response->isSuccess()) {
            $this->events()->trigger(
                'dispatch.post',
                $this,
                array(
                    'error' => false
                )
            );
            return Json::decode($response->getBody(), Json::TYPE_ARRAY);
        }

        $this->events()->trigger(
            'dispatch.post',
            $this,
            array(
                'error' => true,
                'msg'   => $response->getBody()
            )
        );
        return false;
    }

    protected function cacheResults(array $results = array(), $term, $page)
    {
        $articles = $results['results'];
        if(empty($articles) || !$this->container instanceof Container) {
            return false;
        }
        $tmp = array();
        foreach($articles as $article) {
            $id = $article['id'];
            $tmp[$id] = $article;
        }
        $session = $this->container;
        $session->cache = $tmp;
        $session->term  = $term;
        $session->page  = $page;
        $session->count = $results['count'];
        return true;
    }

    protected function getFromCache($term, $page)
    {
        if(!$this->container instanceof Container) {
            return null;
        }
        $session = $this->container;
        if(isset($session->term)) {
            if(isset($session->page)) {
                if(isset($session->count)) {
                    if($session->term == $term && $session->page == $page) {
                        return array(
                            'count' => $session->count,
                            'results' => $session->cache
                        );
                    }
                }
            }
        }
        return null;
    }

    protected function searchCache($id)
    {
        if(!$this->container instanceof Container) {
            return null;
        }
        $article = null;
        $cache = $this->container->cache;
        if(isset($cache[$id])) {
            $article = array(
                'count' => 1,
                'results' => array($cache[$id])
            );
        }
        return $article;
    }
}