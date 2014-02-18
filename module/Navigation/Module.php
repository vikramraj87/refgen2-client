<?php
namespace Navigation;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceManager;
use Zend\Navigation\Navigation;

use Citation\Service\CitationService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $this->prepareNavigation($e);
    }

    /**
     * Prepares the navigation by removing unwanted links
     * based on the state of the application
     */
    protected function prepareNavigation(MvcEvent $e)
    {
        /** @var Application $application */
        $application = $e->getTarget();

        /** @var ServiceManager $serviceManager */
        $serviceManager = $application->getServiceManager();

        /** @var Navigation  $navigation */
        $navigation = $serviceManager->get('Navigation');

        /** @var CitationService $citationService */
        $citationService = $serviceManager->get('CitationService');

        $citationContainer = $navigation->findOneBy('label', 'Citations');
        $deletePage = $citationContainer->findOneBy('label', 'Delete list');
        $savePage   = $citationContainer->findOneBy('label', 'Save list');

        if(!$citationService->isDeletable()) {
            $citationContainer->removePage($deletePage);
        }

        if(!$citationService->isSavable()) {
            $citationContainer->removePage($savePage);
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
            )
        );
    }
}