<?php
namespace Api\Client;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Decoder as JsonDecoder;
use Zend\Json\Json;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Session\Container;
use Zend\Stdlib\Hydrator\ClassMethods;
use Common\Entity\OrderedList;

class ApiClient
{
    protected static $client = null;

    protected static $host = 'http://api.kivirefgen.com';
    protected static $getList = '/pubmed/term/%s/%d';
    protected static $get = '/pubmed/id/%s';


    protected static function getClientInstance()
    {
        if(self::$client === null) {
            self::$client = new Client();
            self::$client->setEncType(Client::ENC_URLENCODED);
        }
        return self::$client;
    }

    public static function getArticle($id)
    {
        $article = self::searchCache($id);
        $response = array();
        if(!is_array($article)) {
            $logger = new Logger();
            $logger->addWriter(new Stream('data/logs/resultscache.log'));
            $logger->debug('article not found in the cache');

            $uri = self::$host . sprintf(self::$get, $id);
            $response = self::doRequest($uri);
        } else {
            $response['count'] = 1;
            $response['results'] = array($article);
        }
        return $response;
    }

    public static function getArticles($term, $page = 1)
    {
        $page = (int) $page;
        $uri = self::$host . sprintf(self::$getList, $term, $page);
        $results = self::doRequest($uri);
        self::cacheResults($results);
        return $results;
    }

    protected static function doRequest($url, array $data = array(), $method = Request::METHOD_GET)
    {
        $client = self::getClientInstance();
        $client->setUri($url);
        $client->setMethod($method);

        if($data !== null) {
            $client->setParameterPost($data);
        }

        $response = $client->send();

        if($response->isSuccess()) {
            return JsonDecoder::decode($response->getBody(), Json::TYPE_ARRAY);
        } else {
            $logger = new Logger();
            $logger->addWriter(new Stream('data/logs/apiclient.log'));
            $logger->debug($response->getBody());
            return false;
        }
    }

    protected static function cacheResults($results = array())
    {
        if(!isset($results['count']) || $results['count'] === 0) {
            if(!isset($results['count'])) {
                $logger = new Logger();
                $logger->addWriter(new Stream('data/logs/resultscache.log'));
                $logger->debug('results not cached because not valid');
            }
            return false;
        }

        $session = new Container('cache_results');

        $articles = array();

        foreach($results['results'] as $articleData) {
            $articleId = $articleData['id'];
            $articles[$articleId] = $articleData;
        }

        $list = new OrderedList();
        $list->setData($articles);

        $session->list = $list;
    }

    protected static function searchCache($id)
    {
        $session = new Container('cache_results');

        $list = $session->list;
        if(isset($list[$id])) {
            return $list[$id];
        }
        return false;
    }
}