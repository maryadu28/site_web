<?php

namespace Admin\Form;

use Laminas\Form\Form;

class FabricForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('fabric');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'fabric',
            'type' => 'text',
            'options' => [
                'label' => 'Matière',
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

