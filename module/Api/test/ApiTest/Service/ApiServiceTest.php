<?php
/**
 * Created by PhpStorm.
 * User: vikramraj
 * Date: 02/02/14
 * Time: 3:37 PM
 */

namespace ApiTest\Service;

use PHPUnit_Framework_TestCase;
use Api\Service\ApiService;
use Zend\Http\Client;
use Zend\Session\Container;

class ApiServiceTest extends PHPUnit_Framework_TestCase
{
    /** @var ApiService */
    protected $service;

    public function setUp()
    {
        $this->service = new ApiService;
        $client = new Client();
        $client->setOptions(array(
            'timeout' => 20
        ));
        $this->service->setClient($client);
        $this->service->setHost('http://api.refgen.loc/');
        $this->service->setCacheContainer(new Container('cache_results'));
    }

    public function testReturnUrl()
    {
        $result = $this->service->search('ca cervix', 2);
        var_dump($result);
        $result = $this->service->getArticle(2);
        var_dump($result);
    }
} 