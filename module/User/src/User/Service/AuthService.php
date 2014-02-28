<?php
/**
 * Created by PhpStorm.
 * User: vikramraj
 * Date: 26/02/14
 * Time: 11:28 PM
 */

namespace User\Service;

use Zend\Authentication\AuthenticationService;

use User\Authentication\Adapter\Api as AuthenticationAdapter;

class AuthService extends AuthenticationService
{
    public function authenticateUser(array $data)
    {
        if(!isset($data['email']) || !isset($data['password'])) {
            throw new \RuntimeException('Invalid data provided for authentication');
        }

        $adapter = $this->getAdapter();
        $adapter->setEmail($data['email'])
                ->setPassword($data['password']);

        return $this->authenticate();
    }
}