<?php

namespace Admin\Form;

use Laminas\Form\Form;

class MarkForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('mark');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'mark',
            'type' => 'text',
            'options' => [
                'label' => 'Marque',
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
