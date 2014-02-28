<?php

namespace UserTest\Authentication\Adapter;

use Zend\Json\Json;
use Zend\Authentication\Result;

use PHPUnit_Framework_TestCase;

use Api\Client\ApiClient;
use User\Authentication\Adapter\Api as Adapter;
use User\Entity\User;


class ApiTest extends PHPUnit_Framework_TestCase
{
    protected $adapter;

    public function setUp()
    {
        $responseStr = '{"status":"success","user":{"id":"1","email":"dr.vikramraj87@gmail.com","first_name":"Vikram Raj","last_name":"Gopinathan","created_at":"2014-01-25 14:05:37","updated_at":null}}';
        $response    = Json::decode($responseStr, Json::TYPE_ARRAY);

        $mockClient = $this->getMock('\Api\Service\ApiService', array('authenticate'));
        $mockClient->expects($this->any())
                   ->method('authenticate')
                   ->will($this->returnValue($response));

        $this->adapter = new Adapter('dr.vikramraj87@gmail.com', 'test');
        $this->adapter->setApi($mockClient);
    }

    public function testSuccessfulLogin()
    {
        /** @var \Zend\Authentication\Result $response */
        $response = $this->adapter->authenticate();
        $this->assertEquals(Result::SUCCESS, $response->getCode());

        /** @var User $user */
        $user = $response->getIdentity();
        $this->assertTrue($user instanceof User);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('dr.vikramraj87@gmail.com', $user->getEmail());
    }

    public function testIncorrectPassword()
    {
        $responseStr = '{"status":"failure","messages":{"password":{"incorrect":"Incorrect password"}}}';
        $response    = Json::decode($responseStr, Json::TYPE_ARRAY);

        $mockClient = $this->getMock('\Api\Service\ApiService', array('authenticate'));
        $mockClient->expects($this->any())
                   ->method('authenticate')
                   ->will($this->returnValue($response));

        $adapter = new Adapter('dr.vikramraj87@gmail.com', 'test');
        $adapter->setApi($mockClient);

        $response = $adapter->authenticate();
        $this->assertEquals(Result::FAILURE_CREDENTIAL_INVALID, $response->getCode());
        $this->assertEquals(null, $response->getIdentity());
    }

    public function testUnregisteredEmail()
    {
        $responseStr = '{"status":"failure","messages":{"email":{"noRecordFound":"User with Email: dr.vikramra87@gmail.com not found"}}}';
        $response    = Json::decode($responseStr, Json::TYPE_ARRAY);

        $mockClient = $this->getMock('\Api\Service\ApiService', array('authenticate'));
        $mockClient->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($response));

        $adapter = new Adapter('dr.vikramra87@gmail.com', 'test');
        $adapter->setApi($mockClient);

        $response = $adapter->authenticate();
        $this->assertEquals(Result::FAILURE_IDENTITY_NOT_FOUND, $response->getCode());
        $this->assertEquals(null, $response->getIdentity());
    }

    public function testInvalidEmail()
    {
        $responseStr = '{"status":"failure","messages":{"email":{"emailAddressInvalidFormat":"The input is not a valid email address. Use the basic format local-part@hostname"}}}';
        $response    = Json::decode($responseStr, Json::TYPE_ARRAY);

        $mockClient = $this->getMock('\Api\Service\ApiService', array('authenticate'));
        $mockClient->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($response));

        $adapter = new Adapter('dr.vikramra87gmail.com', 'test');
        $adapter->setApi($mockClient);

        $response = $adapter->authenticate();
        $this->assertEquals(Result::FAILURE, $response->getCode());
        $this->assertEquals(null, $response->getIdentity());
    }

    public function testInvalidPassword()
    {
        $responseStr = '{"status":"failure","messages":{"password":{"stringLengthTooShort":"Password should be atleast 8 characters long"}}}';
        $response    = Json::decode($responseStr, Json::TYPE_ARRAY);

        $mockClient = $this->getMock('\Api\Service\ApiService', array('authenticate'));
        $mockClient->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($response));

        $adapter = new Adapter('dr.vikramra87@gmail.com', 'test');
        $adapter->setApi($mockClient);

        $response = $adapter->authenticate();
        $this->assertEquals(Result::FAILURE, $response->getCode());
        $this->assertEquals(null, $response->getIdentity());
    }
} 