<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

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
                        'action'     => 'index'
                    )
                )
            )
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            /*
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ), */
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'invokables' => array(
            'citation_generator' => 'Article\Entity\Citation\Vancouver',
            'article_hydrator'   => 'Zend\Stdlib\Hydrator\ClassMethods'
        ),
        'factories' => array(
            'app_di' => function($sm) {
                $di = new \Zend\Di\Di();
                $config = new \Zend\Di\Config(array(
                    'definition' => array(
                        'class' => array(
                            'Article\Entity\Article' => array(
                                'setCitationGenerator' => array(
                                    'required' => true
                                )
                            )
                        )
                    ),
                    'instance' => array(
                        'preference' => array(
                            'Article\Entity\Citation\CitationInterface' =>
                                'Article\Entity\Citation\Vancouver'
                        )
                    )
                ));
                $di->configure($config);
                return $di;
            },
            'zero_results_log' => function($sm) {
                    $log = new \Zend\Log\Logger();
                    $writer = new \Zend\Log\Writer\Stream('data/logs/zeroresults.log');
                    $log->addWriter($writer);
                    return $log;
                }
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
        'max_results' => 10
    ),
    'view_helpers' => array(
        'invokables' => array(
            'truncate' => 'Application\View\Helper\Truncate'
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
