<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\Authentication\Result as AuthResult;

use User\Form\LoginForm,
    User\Filter\LoginFilter,
    User\Service\AuthService,
    User\Entity\User;

class UserController extends AbstractActionController
{
    /** @var AuthService */
    protected $authService;

    public function loginAction()
    {
        $request = $this->getRequest();

        $form = new LoginForm();

        if($request->isPost()) {
            $post = $request->getPost()->toArray();
            if(isset($post['action-login'])) {
                $filter = new LoginFilter();
                $form->setInputFilter($filter);
                $form->setData($post);

                if($form->isValid()) {
                    $data = $form->getData();

                    /** @var AuthResult $result */
                    $result = $this->getAuthService()->authenticateUser($data);
                    if($result->isValid()) {
                        return $this->redirect()->toUrl('/');
                    }
                    $form->setMessages($result->getMessages());
                }
            }
        }
        $this->layout()->setTemplate('layout/single.phtml');

        return array(
            'form' => $form
        );
    }

    public function indexAction()
    {
        $id = 'Guest';
        if($this->getAuthService()->hasIdentity()) {
            /** @var User $user */
            $user = $this->getAuthService()->getIdentity();
            $id = $user->getFirstName() . ' ' . $user->getLastName();
        }
        return array(
            'id' => $id
        );
    }

    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        return $this->redirect()->toUrl('/');
    }

    protected function getAuthService()
    {
        if($this->authService === null) {
            $this->authService = $this->getServiceLocator()->get('User\Service\Auth');
        }
        return $this->authService;
    }
} 