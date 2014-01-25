<?php
return array(
    'router' => array(
        'routes' => array(
            'citation\add' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/citation/add/[:id]',
                    'constraints' => array(
                        'id' => '\d+'
                    ),
                    'defaults' => array(
                        'controller' => 'Citation\Controller\Index',
                        'action'     => 'add'
                    )
                )
            ),
            'citation\remove' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/citation/remove/[:id]',
                    'constraints' => array(
                        'id' => '\d+'
                    ),
                    'defaults' => array(
                        'controller' => 'Citation\Controller\Index',
                        'action'     => 'remove'
                    )
                )
            ),
            'citation\get' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/citation/get',
                    'constraints' => array(
                        'id' => '\d+'
                    ),
                    'defaults' => array(
                        'controller' => 'Citation\Controller\Index',
                        'action'     => 'get'
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