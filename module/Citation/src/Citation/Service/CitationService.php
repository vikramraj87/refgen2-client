<?php
namespace Citation\Service;

use Zend\Session\Container;
use Common\Entity\OrderedList;
use Api\Client\ApiClient;
use Zend\Stdlib\Hydrator\ClassMethods;

use Citation\Entity\Collection;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;


class CitationService implements ServiceLocatorAwareInterface
{
    /** @var Container */
    protected $container  = null;

    /** @var ServiceLocatorInterface */
    protected $services;

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


    public function add($id)
    {
        /** @var \Common\Entity\OrderedList $list */
        $list = $this->getContainer()->tmp;

        if(!isset($list[$id])) {
            /** @var array $result */
            $result = ApiClient::getArticle($id);

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
                $list[$article->getId()] = $article->getCitation();
                $this->getContainer()->changed = true;
                return true;
            }
        }
        return false;
    }

    public function remove($id)
    {
        /** @var \Common\Entity\OrderedList $list */
        $list = $this->getContainer()->tmp;

        if(isset($list[$id])) {
            unset($list[$id]);
            $this->getContainer()->changed = true;
            return true;
        }
        return false;
    }

    public function update()
    {
        $activeListId = $this->getContainer()->activeListId;
        if($activeListId === 0) {
            throw new \RuntimeException('No active list selected for updating');
        }
        // update the list

        $this->getContainer()->changed = false;
        return true;
    }

    public function save()
    {
        $activeListId = $this->getContainer()->activeListId;
        if($activeListId === 0) {
            throw new \RuntimeException('No active list selected for saving');
        }
        // save the active list

        $this->getContainer()->changed = false;
        return true;
    }

    public function isChanged()
    {
        return $this->getContainer()->changed;
    }

    /**
     * @param string $collectionId
     * @return Collection
     * @throws \RuntimeException
     */
    public function getCollection ($collectionId)
    {
        $userId = 1; // should come from the session
        $collectionData = ApiClient::getCollection($collectionId, $userId);

        if(!isset($collectionData['id'])) {
            throw new \RuntimeException('No data returned from the server');
        }

        /** @var Collection $collection */
        $collection = new Collection($collectionData['id'], $collectionData['user_id']);

        $collection->setName($collectionData['name']);
        $collection->setCreatedAt($collectionData['created_at']);
        $collection->setUpdatedAt($collectionData['updated_at']);

        $articles = array();
        $hydrator = new ClassMethods();
        $di = $this->getServiceLocator()->get('app_di');
        foreach($collectionData['articles'] as $articleData) {
            $articles[] = $hydrator->hydrate($articleData, $di->newInstance('Article\Entity\Article'));
        }
        $collection->setArticles($articles);

        $this->setActiveList($collection);

        return $collection;
    }

    protected function checkList($listId)
    {
        $userId = 1; // should come from the session
    }

    public function delete($listId)
    {
        $listId = (int) $listId;
    }

    public function rename($listId)
    {
        $listId = (int) $listId;
    }

    public function getActiveListId()
    {
        $container = $this->getContainer();
        return array(
            'id' => $container->activeListId,
            'name' => $container->activeListName
        );
    }

    public function getAll()
    {
        return $this->getContainer()->tmp;
    }


    protected function setActiveList(Collection $collection)
    {
        $container = $this->getContainer();
        if($container->activeListId == $collection->getId()) {
            return;
        }
        $container->activeListId = $collection->getId();
        $container->activeListName = $collection->getName();
        $tmp = new OrderedList();
        foreach($collection->getArticles() as $article) {
            $tmp[$article->getId()] = $article->getCitation();
        }
        $container->tmp = $tmp;
        $container->changed = false;
    }

    protected function getContainer()
    {
        if($this->container === null) {
            $this->container = new Container('citations');
            if(!isset($this->container->tmp) ||
               !$this->container->tmp instanceof OrderedList)
            {
                $this->container->tmp = new OrderedList();
                $this->container->changed = false;
                $this->container->activeListId = 0;
                $this->container->activeListName = '';
            }
        }
        return $this->container;
    }
}