<?php
namespace Citation\Form;

use Zend\Form\Form;

class OpenForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('open-collection');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'open-collection');

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Open an already existing collection'
            ),
            'attributes' => array(
                'id' => 'collection-id',
            )
        ));

        $this->add(array(
            'name' => 'action-open',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'action-open',
                'value' => 'Open'
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