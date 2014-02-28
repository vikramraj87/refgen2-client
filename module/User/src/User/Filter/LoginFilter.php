<?php
namespace User\Filter;

use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 6,
                            'max' => 254
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array('name' => 'EmailAddress')
                )
            )
        );

        $this->add(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 8,
                            'max' => 24,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Password should be atleast 8 characters long',
                                StringLength::TOO_LONG => 'Password should be no more than 24 characters long'
                            )
                        )
                    )
                )
            )
        );
    }
} 