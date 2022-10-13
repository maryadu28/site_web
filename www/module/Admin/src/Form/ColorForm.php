<?php

namespace Admin\Form;

use Laminas\Form\Form;

class ColorForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('color');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'color',
            'type' => 'text',
            'options' => [
                'label' => 'Couleur',
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

