<?php
/**
 * Created by PhpStorm.
 * User: vikramraj
 * Date: 25/02/14
 * Time: 9:59 PM
 */

namespace UserTest\Entity;


use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Json\Json;

use PHPUnit_Framework_TestCase;

use User\Entity\User;


class UserTest extends PHPUnit_Framework_TestCase
{
    /** @var array Response */
    protected $response;

    /** @var array User data */
    protected $userData;

    public function setUp()
    {
        //$response = '{"status":"success","user":{"id":"1","email":"dr.vikramraj87@gmail.com","first_name":"Vikram Raj","last_name":"Gopinathan","created_at":"2014-01-25 14:05:37","updated_at":"2014-01-25 14:05:37"}}';
        $response = '{"status":"success","user":{"id":"1","email":"dr.vikramraj87@gmail.com","first_name":"Vikram Raj","last_name":"Gopinathan","created_at":"2014-01-25 14:05:37","updated_at":null}}';
        $this->response = Json::decode($response, Json::TYPE_ARRAY);

        $response = $this->response;
        if($response['status'] === 'success') {
            $this->userData = $response['user'];
        }
    }

    public function testInitWithHydrator()
    {
        $hydrator = new ClassMethods;

        /** @var User $user */
        $user = $hydrator->hydrate($this->userData, new User);


        $this->assertEquals($this->userData['id'], $user->getId());
        $this->assertEquals($this->userData['email'], $user->getEmail());
        $this->assertEquals($this->userData['first_name'], $user->getFirstName());
        $this->assertEquals($this->userData['last_name'], $user->getLastName());
        $this->assertEquals(new \DateTime($this->userData['created_at']), $user->getCreatedAt());
        $this->assertEquals(null, $user->getUpdatedAt());
    }
} 