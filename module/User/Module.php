<?php
namespace User;

use User\Authentication\Adapter\Api as AuthenticationAdapter;
use User\Service\AuthService;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'User\Authentication\Adapter' => function($sm) {
                    $adapter = new AuthenticationAdapter;
                    $apiService = $sm->get('ApiService');
                    $adapter->setApi($apiService);
                    return $adapter;
                },
                'User\Service\Auth' => function($sm) {
                    /** @var AuthenticationAdapter $adapter */
                    $adapter = $sm->get('User\Authentication\Adapter');
                    $authService = new AuthService;
                    $authService->setAdapter($adapter);
                    return $authService;
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