<?php
namespace UserTest\Service;

use Zend\Json\Json,
    Zend\Authentication\Result;

use User\Service\AuthService,
    User\Authentication\Adapter\Api as AuthAdapter;

use PHPUnit_Framework_TestCase;

class AuthServiceTest extends PHPUnit_Framework_TestCase
{
    /** @var AuthService */
    protected $service;

    public function setUp()
    {
        $successJson = '{"status":"success","user":{"id":"1","email":"dr.vikramraj87@gmail.com","first_name":"Vikram Raj","last_name":"Gopinathan","created_at":"2014-01-25 14:05:37","updated_at":null}}';
        $success = Json::decode($successJson, Json::TYPE_ARRAY);
        $mockApiService = $this->getMock('\Api\Service\ApiService', array('authenticate'));
        $mockApiService->expects($this->any())
                       ->method('authenticate')
                       ->will($this->returnValue($success));
        $authAdapter = new AuthAdapter;
        $authAdapter->setApi($mockApiService);

        $this->service = new AuthService();
        $this->service->setAdapter($authAdapter);
    }

    public function testWithMockPositiveResponse()
    {
        $response = $this->service->authenticateUser(array(
            'email' => 'dr.vikramrj87@gmail.com',
            'password' => 'K1rth1k@s1n1'
        ));
        $this->assertEquals(Result::SUCCESS, $response->getCode());
    }


} 