<?php

namespace Admin\Form;

use Laminas\Form\Form;

class BookForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('book');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);
        $this->add([
            'name' => 'author',
            'type' => 'text',
            'options' => [
                'label' => 'Author',
            ],
        ]);
        $this->add([
            'name' => 'summary',
            'type' => 'text',
            'options' => [
                'label' => 'Summary',
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
