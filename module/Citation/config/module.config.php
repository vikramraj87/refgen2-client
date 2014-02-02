<?php
return array(
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
                            'route' => '/get[/:id]',
                            'constraints' => array(
                                'id' => '\d+'
                            ),
                            'defaults' => array(
                                'id' => '0',
                                'action' => 'get'
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
        'invokables' => array(
            'citation_service' => 'Citation\Service\CitationService'
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
                $service = new \Citation\Service\CitationService();
                $service->setServiceLocator($sm);
                return new \Citation\View\Helper\Citations($service);
            }
        )
    )
);