<?php
namespace User\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Stdlib\Hydrator\ClassMethods;

use Api\Service\ApiService;
use User\Entity\User;

class Api implements AdapterInterface
{
    /** @var string */
    protected $email;

    /** @var string */
    protected $password;

    /** @var ApiService */
    protected $api;

    /**
     * @param ApiService $api
     * @return $this
     */
    public function setApi(ApiService $api)
    {
        $this->api = $api;
        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return Result
     */
    public function authenticate()
    {
        $response = $this->api->authenticate($this->email, $this->password);

        /** @var int $code */
        $code = Result::FAILURE;

        /** @var null|User $user */
        $user = null;

        if($response['status'] === 'success') {
            $userData = $response['user'];
            $hydrator = new ClassMethods;

            $user = $hydrator->hydrate($userData, new User());
            $code = Result::SUCCESS;
        }

        $messages = isset($response['messages']) ? $response['messages'] : array();
        switch (true) {
            case isset($messages['password']['incorrect']):
                $code = Result::FAILURE_CREDENTIAL_INVALID;
                break;
            case isset($messages['email']['noRecordFound']):
                $code = Result::FAILURE_IDENTITY_NOT_FOUND;
                break;
        }
        return new Result($code, $user, $messages);
    }
}