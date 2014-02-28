<?php
namespace AclTest\Service;

use Acl\Service\AclService;

use PHPUnit_Framework_TestCase;

class AclServiceTest extends PHPUnit_Framework_TestCase
{
    /** @var AclService */
    protected $service;

    public function setUp()
    {
        $config = array(
            'permissions' => array(
                'guest' => array(
                    'user' => array(
                        'login',
                        'sign-up'
                    )
                ),
                'member' => array(
                    'user' => array(
                        'logout'
                    ),
                    'citation'
                )
            )
        );
        $this->service = new AclService($config);


    }

    public function testAclConfigurationForMember()
    {
        $mockAuthService = $this->getMock('\Zend\Authentication\AuthenticationService', array('hasIdentity'));
        $mockAuthService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(true));

        /** @var AclService $service */
        $service = $this->service;

        $service->setAuthService($mockAuthService);

        $this->assertTrue($service->hasAccess('user', 'logout'));
        $this->assertTrue($service->hasAccess('citation', 'new'));
        $this->assertTrue($service->hasAccess('citation', 'open'));
        $this->assertTrue($service->hasAccess('citation', 'save'));
        $this->assertTrue($service->hasAccess('citation', 'save-as'));
        $this->assertTrue($service->hasAccess('citation', 'delete'));

        $this->assertFalse($service->hasAccess('user', 'login'));
        $this->assertFalse($service->hasAccess('user', 'sign-up'));


    }

    public function testAclConfigurationForGuest()
    {
        $mockAuthService = $this->getMock('\Zend\Authentication\AuthenticationService', array('hasIdentity'));
        $mockAuthService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(false));

        /** @var AclService $service */
        $service = $this->service;

        $service->setAuthService($mockAuthService);

        $this->assertFalse($service->hasAccess('user', 'logout'));
        $this->assertFalse($service->hasAccess('citation', 'new'));
        $this->assertFalse($service->hasAccess('citation', 'open'));
        $this->assertFalse($service->hasAccess('citation', 'save'));
        $this->assertFalse($service->hasAccess('citation', 'save-as'));
        $this->assertFalse($service->hasAccess('citation', 'delete'));

        $this->assertTrue($service->hasAccess('user', 'login'));
        $this->assertTrue($service->hasAccess('user', 'sign-up'));


    }
} 