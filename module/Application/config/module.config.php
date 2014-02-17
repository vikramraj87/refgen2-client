<?php
use Zend\View\HelperPluginManager;
use Zend\Http\Request;
use Zend\Mvc\Router\Http\TreeRouteStack as Router;
use Application\View\Helper\SelfUrl;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'search' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/search[/:term][/:page]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Search',
                        'action'     => 'index',
                        'page'       => 1
                    )
                )
            )
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Search' => 'Application\Controller\SearchController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/main'             => __DIR__ . '/../view/layout/main.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'results' => array(
        'max_results' => 10,
        'page_range'  => 10
    ),
    'view_helpers' => array(
        'invokables' => array(
            'truncate' => 'Application\View\Helper\Truncate'
        ),
        'factories' => array(
            'selfUrl' => function(HelperPluginManager $pm) {
                $sm = $pm->getServiceLocator();
                /** @var Router $router */
                $router = $sm->get('Router');

                /** @var Request $request */
                $request = $sm->get('Request');

                return new SelfUrl($router, $request);
            }
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
