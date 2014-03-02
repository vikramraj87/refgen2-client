<?php
namespace Acl;

use Zend\Mvc\MvcEvent,
    Zend\Mvc\Router\RouteMatch;

use Acl\Service\AclService;

class Module {
    public function onBootstrap(MvcEvent $e)
    {
        $events = $e->getApplication()
                    ->getEventManager();
        $events->attach('route', array($this, 'checkAcl'));
    }

    public function checkAcl(MvcEvent $e)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();

        /** @var AclService $aclService */
        $aclService = $sm->get('Acl\Service\Acl');

        /** @var RouteMatch $routeMatch */
        $routeMatch = $e->getRouteMatch();

        $resource = $routeMatch->getParam('controller');
        $privilege = $routeMatch->getParam('action');

        if($aclService->getAcl()->hasResource($resource)) {
            if(!$aclService->hasAccess($resource, $privilege)) {
                $response = $e->getResponse();
                $response->setStatusCode(404);
                return;
            }
        }
    }

    public function getConfig()
    {
        return array();
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Acl\Service\Acl' => function($sm) {
                    $config = $sm->get('config');
                    $authService = $sm->get('User\Service\Auth');

                    $service = new AclService($config['acl']);
                    $service->setAuthService($authService);
                    return $service;
                }
            )
        );
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

} 