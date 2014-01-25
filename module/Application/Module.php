<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->bootstrapSession($e);
    }

    public function bootstrapSession(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();

        /** @var SessionManager $session */
        $session = $sm->get('session_manager');
        Container::setDefaultManager($session);
        $session->start();

        /** @var Container $container */
        $container = new Container('initialized');

        if(!isset($container->init)) {
            $session->regenerateId(true);
            $container->init = 1;
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
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
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
                },
                'session_db_save_handler' => function($sm) {
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $tableGateway = new TableGateway('session', $adapter);
                    $saveHandler = new DbTableGateway($tableGateway, new DbTableGatewayOptions());
                    return $saveHandler;
                },
                'session_manager' => function($sm) {
                    $sessionManager = new SessionManager();
                    $saveHandler = $sm->get('session_db_save_handler');
                    $sessionManager->setSaveHandler($saveHandler);
                    return $sessionManager;
                }
            ),
        );
    }
}
