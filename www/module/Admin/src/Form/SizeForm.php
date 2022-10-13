<?php

namespace Admin\Form;

use Laminas\Form\Form;

class SizeForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('size');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'size',
            'type' => 'text',
            'options' => [
                'label' => 'Taille',
            ],
        ]);
     
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}

