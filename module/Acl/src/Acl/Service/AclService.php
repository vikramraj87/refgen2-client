<?php
/**
 * Created by PhpStorm.
 * User: vikramraj
 * Date: 27/02/14
 * Time: 6:27 PM
 */

namespace Acl\Service;

use Zend\Permissions\Acl\Acl,
    Zend\Authentication\AuthenticationService,
    Zend\Permissions\Acl\Role\GenericRole as Role;

class AclService
{
    const GUEST = 'guest';
    const MEMBER = 'member';

    /** @var AuthenticationService */
    protected $authService;

    /** @var Acl */
    protected $acl;

    public function __construct(array $config = array())
    {
        $this->configure($config);
    }

    /**
     * @param AuthenticationService $service
     * @return $this
     */
    public function setAuthService(AuthenticationService $service)
    {
        $this->authService = $service;
        return $this;
    }

    /**
     * @return AuthenticationService
     * @throws \RuntimeException
     */
    protected function getAuthService()
    {
        if($this->authService === null) {
            throw new \RuntimeException('Authentication service not set');
        }
        return $this->authService;
    }

    public function getAcl()
    {
        if($this->acl === null) {
            throw new \RuntimeException('Acl not configured properly');
        }
        return $this->acl;
    }

    public function hasAccess($resource, $privilege = null)
    {
        return $this->getAcl()->isAllowed($this->getRole(), $resource, $privilege);
    }

    protected function configure(array $config = array())
    {
        if($this->acl != null) {
            return;
        }
        $this->acl = new Acl();

        if(!isset($config['permissions']) || !is_array($config['permissions'])) {
            throw new \RuntimeException('Invalid config file provided');
        }

        $permissions = $config['permissions'];

        $reflection = new \ReflectionClass($this);
        $roles = $reflection->getConstants();
        $acl = $this->getAcl();
        foreach($roles as $role) {
            if(!$acl->hasRole($role)) {
                $role = new Role($role);
                $acl->addRole($role);
            } else {
                $role = $acl->getRole($role);
            }
            if(array_key_exists($role->getRoleId(), $permissions)) {
                foreach($permissions[$role->getRoleId()] as $resource => $privileges) {
                    if(is_array($privileges)) {
                        if(!$acl->hasResource($resource)) {
                            $acl->addResource($resource);
                        }
                        $acl->allow($role, $resource, $privileges);
                    } else {
                        $resource = $privileges;
                        if(!$acl->hasResource($resource)) {
                            $acl->addResource($resource);
                        }
                        $acl->allow($role, $resource, NULL);
                    }
                }
            }
        }
    }

    /**
     * @return string Role Id based on whether the authentication service has identity or not
     */
    public function getRole()
    {
        $roleId = self::GUEST;

        if($this->getAuthService()->hasIdentity()) {
            $roleId = self::MEMBER;
        }

        return $roleId;
    }


} 