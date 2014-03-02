<?php
namespace Api;
use Api\Service\ApiService;

use Zend\Http\Client;
use Zend\Session\Container;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\EventManager\Event;
use Zend\Http\Client\Adapter\Curl;

use \DateTime;


class Module
{
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

    public function getConfig()
    {
        return array(
            'api' => array(
                'host' => 'http://api.rg.loc',
                'client-id' => 'web-client',
                'client-secret' => 'secret'
            )
        );
    }
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ApiService' => function($sm) {
                    $config = $sm->get('config');

                    $host = isset($config['api']['host']) ? $config['api']['host'] : 'http://api.kivirefgen.com';

                    $container = new Container('results_cache');

                    /** @var Container $authContainer */
                    $authContainer = new Container('oauth');


                    $client = new Client();
                    $client->setOptions(array(
                        'timeout' => 20
                    ));


                    $service = new ApiService();
                    $service->setHost($host)
                            ->setClient($client)
                            ->setCacheContainer($container)
                            ->setAuthContainer($authContainer)
                            ->setClientId($config['api']['client-id'])
                            ->setClientSecret($config['api']['client-secret']);

                    $service->events()->attach(
                        'dispatch.pre',
                        function(Event $event) use($sm) {
                            $logger = $sm->get('DispatchLogger');
                            $logger->debug($event->getParam('request'));
                        }
                    );

                    return $service;
                },

                'DispatchLogger' => function($sm) {
                    $logger = new Logger();
                    $date = new DateTime('NOW');
                    $fn = $date->format('Y-m-d') . '.log';
                    $writer = new Stream('data/logs/dispatch/' . $fn, 'a+');
                    $logger->addWriter($writer);
                    return $logger;
                }
            )
        );
    }
}