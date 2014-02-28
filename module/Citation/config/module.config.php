<?php
use Citation\Service\CitationService;
return array(
    'acl' => array(
        'permissions' => array(
            'guest' => array(
                'Citation\Controller\Index' => array(
                    'add',
                    'remove'
                )
            ),
            'member' => array(
                'Citation\Controller\Index'
            )
        )
    )
    ,
    'router' => array(
        'routes' => array(
            'citation' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/citation',
                    'defaults' => array(
                        'controller' => 'Citation\Controller\Index'
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'add' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/add/[:id]',
                            'constraints' => array(
                                'id' => '\d+'
                            ),
                            'defaults' => array(
                                'id' => '0',
                                'action' => 'add'
                            )
                        )
                    ),
                    'remove' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/remove/[:id]',
                            'constraints' => array(
                                'id' => '\d+'
                            ),
                            'defaults' => array(
                                'id' => '0',
                                'action' => 'remove'
                            )
                        )
                    ),
                    'get' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/get/:id[/:page]',
                            'constraints' => array(
                                'id' => '\d+',
                                'page' => '\d+'
                            ),
                            'defaults' => array(
                                'id' => '0',
                                'page' => '1',
                                'action' => 'get'
                            )
                        )
                    ),
                    'open' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/open[/:id]',
                            'constraints' => array(
                                'id' => '\d+'
                            ),
                            'defaults' => array(
                                'id' => '0',
                                'action' => 'open'
                            )
                        )
                    ),
                    'default' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[:action]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Citation/Service' => function($sm) {
                $service = new CitationService();
                $service->setServiceLocator($sm);
                return $service;
            }
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Citation\Controller\Index' => 'Citation\Controller\IndexController'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'citations' => function ($sm) {
                /** @var \Citation\Service\CitationService $service */
                $service = $sm->getServiceLocator()->get('CitationService');
                return new \Citation\View\Helper\Citations($service);
            }
        )
    )
);