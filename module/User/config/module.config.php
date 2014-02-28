<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
            'User\Controller\Index' => 'User\Controller\IndexController'
        )
    ),
    'acl' => array(
        'permissions' => array(
            'guest' => array(
                'User\Controller\User' => array(
                    'login',
                    'sign-up'
                )
            ),
            'member' => array(
                'User\Controller\User' => array(
                    'logout'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'action' => 'login'
                            )
                        )
                    ),
                    'logout' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'action' => 'logout'
                            )
                        )
                    )
                )
            )
        )
    )
);