<?php
namespace Citation\Service;

use Zend\Session\Container;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

use Api\Service\ApiService;

use Citation\Entity\Collection;
use Citation\Entity\ActiveCollection;

use Article\Entity\Article;

//todo: remove invalid server response exception
class CitationService implements ServiceLocatorAwareInterface
{
    /** @var Container */
    protected $container  = null;

    /** @var ServiceLocatorInterface */
    protected $services;

    /** @var ApiService */
    protected $apiService = null;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->services;
    }

    /**
     * Adds an article to the active collection
     *
     * @param $id
     * @return bool
     * @throws \RuntimeException
     */
    public function add($id)
    {
        /** @var Collection $collection */
        $collection = $this->getActiveCollection();

        if(!isset($collection[$id])) {
            /** @var array $result */
            $result = $this->getApiService()->getArticle($id);

            if(!isset($result['count'])) {
                throw new \RuntimeException('Invalid data returned from the server');
            }

            /** @var \Article\Entity\Article|null $article */
            $article = null;

            if($result['count'] === 1) {
                /** @var \Zend\Stdlib\Hydrator\ClassMethods $hydrator */
                $hydrator = new ClassMethods();

                /** @var \Zend\Di\Di $di */
                $di = $this->getServiceLocator()->get('app_di');

                $article = $hydrator->hydrate(
                    $result['results'][0], $di->newInstance('Article\Entity\Article')
                );
            }

            if($article !== null) {
                $collection[$article->getId()] = $article;
                return true;
            }
        }
        return false;
    }

    /**
     * Removes an article from the active collection
     *
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        /** @var Collection $collection */
        $collection = $this->getActiveCollection();

        if(isset($collection[$id])) {
            unset($collection[$id]);
            return true;
        }
        return false;
    }

    /**
     * Clears the existing collection and create a new blank collection
     */
    public function newCollection()
    {
        $this->getContainer()->activeCollection = new ActiveCollection();
    }

    /**
     * Opens the collection and set it as active collection
     *
     * @param $id
     * @return bool
     */
    public function openCollection($id)
    {
        $collection = $this->getCollection($id);
        $this->setActiveCollection($collection);
        return true;
    }

    /**
     * Gets the collection data from the API
     *
     * @param $id
     * @return Collection
     * @throws \RuntimeException
     */
    public function getCollection($id)
    {
        $id = (int) $id;
        $userId = 1; // should come from session

        $response = $this->getApiService()->getCollection($id, $userId);

        if($response['status'] != 'success') {
            $messages = '';
            foreach($response['messages'] as $message) {
                $messages .= implode('; ', $message);
            }
            throw new \RuntimeException($messages);
        }

        $collectionData = $response['collection'];
        if(!isset($collectionData['id'])) {
            throw new \RuntimeException('Invalid data returned from the server');
        }

        $hydrator = new ClassMethods();

        $articlesData = $collectionData['articles'];
        $articles = array();

        /** @var \Zend\Di\Di $di */
        $di = $this->getServiceLocator()->get('app_di');
        foreach($articlesData as $articleData) {
            $articles[] = $hydrator->hydrate($articleData, $di->newInstance('Article\Entity\Article'));
        }
        $collectionData['articles'] = $articles;
        $collection = $hydrator->hydrate($collectionData, new Collection());
        return $collection;
    }

    /**
     * Fetches all the collections of the current user from the API
     *
     * @return array
     */
    public function getCollections()
    {
        $userId = 1; // should come from the session
        return $this->getApiService()->getCollections($userId);
    }

    /**
     * Saves the current active collection if its already saved
     *
     * @return bool|mixed
     */
    public function saveCollection()
    {
        $collection = $this->getActiveCollection();
        $id     = $collection->getId();
        $userId = 1; // should come from the session

        if($id == null) {
            return false;
        }

        if(!$collection->isChanged()) {
            return true;
        }

        $ids = array();
        if(count($collection)) {
            $ids = array_keys($collection->getCitations());
        }

        $data = array(
            'name'     => $collection->getName(),
            'articles' => implode(',', $ids)
        );

        $response = $this->getApiService()->updateCollection($id, $userId, $data);

        if(isset($response['status']) && $response['status'] === 'success') {
            $collection->resetChanged();
            return true;
        }
        return $response;
    }

    /**
     * Saves a new unsaved collection
     *
     * @param $name
     * @return bool
     */
    public function saveCollectionAs($name)
    {
        $collection = $this->getActiveCollection();
        $userId = 1; // should come from the session

        $ids = array();
        if(count($collection)) {
            $ids = array_keys($collection->getCitations());
        }

        $data = array(
            'name'     => $name,
            'articles' => implode(',', $ids)
        );

        $response = $this->getApiService()->createCollection($userId, $data);

        if(isset($response['status']) && $response['status'] === 'success') {
            $collectionData = $response['collection'];

            $collection->setId($collectionData['id']);
            $collection->setName($collectionData['name']);
            $collection->setCreatedAt($collectionData['created_at']);
            $collection->resetChanged();
            return true;
        }
        return isset($response['messages']) ? $response['messages'] : false;
    }

    /**
     * Deletes the active collection
     *
     * @return bool
     */
    public function deleteCollection()
    {
        $collection = $this->getActiveCollection();
        $id = $collection->getId();
        if(is_null($id)) {
            $this->newCollection();
            return true;
        }
        $userId = 1; //should come from the session
        $response = $this->getApiService()->deleteCollection($id, $userId);
        if(isset($response['status']) && $response['status'] == 'success') {
            $this->newCollection();
            return true;
        }
        return isset($response['messages']) ? $response['messages'] : false;
    }

    /**
     * Sets a collection as active collection
     *
     * @param Collection $collection
     */
    protected function setActiveCollection(Collection $collection)
    {
        $activeCollection = new ActiveCollection($collection);
        $this->getContainer()->activeCollection = $activeCollection;
    }

    /**
     * Getter for active collection
     *
     * @return ActiveCollection
     */
    public function getActiveCollection()
    {
        $container = $this->getContainer();
        if(!isset($container->activeCollection) ||
            !$container->activeCollection instanceof ActiveCollection)
        {
            $container->activeCollection = new ActiveCollection();
        }
        return $container->activeCollection;
    }

    /**
     * Delegate for ActiveCollection->isChanged()
     *
     * @return bool
     */
    public function isChanged()
    {
        return $this->getActiveCollection()->isChanged();
    }

    /**
     * Checks whether the article is already added to the active collection
     *
     * @param Article $article
     * @return bool
     */
    public function isAdded(Article $article)
    {
        $collection = $this->getActiveCollection();
        return isset($collection[$article->getId()]);
    }

    /**
     * Indicates whether the active collection can be deleted from the database
     *
     * @return bool
     */
    public function isDeletable()
    {
        return $this->getActiveCollection()->getId() != null;
    }

    /**
     * Indicates whether the changes in the active collection can be saved in the database
     *
     * @return bool
     */
    public function isSavable()
    {
        return $this->getActiveCollection()->getId() != null && $this->getActiveCollection()->isChanged();
    }

    /**
     * Gets the session container [Factory for container]
     *
     * @return Container
     */
    protected function getContainer()
    {
        if($this->container === null) {
            $this->container = new Container('citations');
        }
        return $this->container;
    }

    /**
     * Factory for Api Service
     *
     * @return ApiService
     */
    protected function getApiService()
    {
        if($this->apiService === null) {
            $this->apiService = $this->getServiceLocator()->get('ApiService');
        }
        return $this->apiService;
    }
}