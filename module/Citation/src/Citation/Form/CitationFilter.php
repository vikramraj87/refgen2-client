<?php

namespace Citation\Form;

use Zend\InputFilter\InputFilter;


class CitationFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'collection-name',
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
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100
                    )
                ),
                array(
                    'name' => 'Alnum',
                    'options' => array(
                        'allowWhiteSpace' => true
                    )
                )
            )
        ));
    }
} 