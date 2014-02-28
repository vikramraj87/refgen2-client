<?php
namespace Acl;

use Acl\Service\AclService;

class Module {
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