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
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Create a new collection with the selected articles'
            ),
            'attributes' => array(
                'placeholder' => 'Collection Name',
                'id' => 'name'
            )
        ));

        $this->add(array(
            'name' => 'action-save',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'action-save',
                'value' => 'Save'
            )
        ));

        $this->add(array(
            'name' => 'action-cancel',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'action-cancel',
                'value' => 'Cancel'
            )
        ));
    }
}