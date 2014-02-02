<?php
namespace Api;
use Api\Service\ApiService;
use Zend\Http\Client;
use Zend\Session\Container;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use \DateTime;
use Zend\EventManager\Event;

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
                'host' => 'http://api.refgen.loc'
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

                    $client = new Client();
                    $client->setOptions(array(
                        'timeout' => 20
                    ));

                    $service = new ApiService();
                    $service->setHost($host)
                            ->setClient($client)
                            ->setCacheContainer($container);

                    $service->events()->attach(
                        'dispatch.pre',
                        function(Event $event) use($sm) {
                            $logger = $sm->get('DispatchLogger');

                            $params = $event->getParam('data');
                            $q = array();
                            foreach($params as $param => $value) {
                                $q[] = $param . '=' . $value;
                            }
                            $data = implode('&', $q);
                            $url = $event->getParam('url');
                            $method = $event->getParam('method');
                            $logger->debug('url: ' . $url . '; method: ' . $method . '; params: ' . $data);
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