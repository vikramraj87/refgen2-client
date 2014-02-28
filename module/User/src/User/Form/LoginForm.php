<?php

namespace User\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'user-login');

        $this->add(array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'options' => array(
                    'label' => 'Email'
                ),
                'attributes' => array(
                    'id' => 'email',
                    'placeholder' => 'youremail@domain.com'
                )
            )
        );

        $this->add(array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' => 'Password'
                ),
                'attributes' => array(
                    'id' => 'password',
                    'placeholder' => 'password'
                )
            )
        );

        $this->add(array(
                'name' => 'action-login',
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'id' => 'action-login',
                    'value' => 'Login'
                )
            )
        );

        $this->add(array(
                'name' => 'action-cancel',
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'id' => 'action-cancel',
                    'value' => 'Cancel'
                )
            )
        );
    }
} 