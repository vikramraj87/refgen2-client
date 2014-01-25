<?php
namespace Citation\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Common\Entity\OrderedList;
use Api\Client\ApiClient;
use Zend\Stdlib\Hydrator\ClassMethods;

use Zend\ServiceManager\ServiceLocatorAwareInterface;


class CitationService implements ServiceLocatorAwareInterface
{
    protected $activeList = null;

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
        $list = $this->getActiveList();

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
                $list[$article->getId()] = $article;
                return true;
            }
        }
        return false;
    }

    public function remove($id)
    {
        /** @var \Common\Entity\OrderedList $list */
        $list = $this->getActiveList();

        if(isset($list[$id])) {
            unset($list[$id]);
            return true;
        }
        return false;
    }

    public function getAll()
    {
        return $this->getActiveList();
    }

    protected function getActiveList()
    {
        if($this->activeList === null) {
            $container = new Container('citation');
            if(!isset($container->tmp) || !$container->tmp instanceof OrderedList) {
                $container->tmp = new OrderedList();
            }
            $this->activeList = $container->tmp;
        }
        return $this->activeList;
    }
}