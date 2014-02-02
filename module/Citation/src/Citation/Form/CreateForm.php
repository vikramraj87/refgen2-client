<?php
namespace Citation\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class CreateForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('create-collection');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'create-collection');


        $this->add(array(
            'name' => 'collection-name',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Create a new collection with the selected articles'
            ),
            'attributes' => array(
                'placeholder' => 'Collection Name',
                'id' => 'name',
                'required' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'submit',
                'value' => 'Create'
            )
        ));
    }
}