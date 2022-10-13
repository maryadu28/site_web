<?php

namespace Admin\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class AdminForm extends Form
{
    public function __construct($groups)
    {

        $this->groups = $groups;

        // We will ignore the name provided to the constructor
        parent::__construct('admin');
        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $options = array();

        foreach ($groups as $group) {
            //name de la table tableGroup
            //( dans controller add ==>$groups = $this->groupTable->fetchAll();)
            $options[$group->id] = $group->name;
        }



        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Nom et Email',
            ],
        ]);


        $this->add([
            'name' => 'id_group',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Groupe',
                'value_options' => $options,


            ],
        ]);


        $this->add([
            'name' => 'img',
            'type' => 'file',
            'options' => [
                'label' => 'Image',

            ],
        ]);
        $this->add([
            'name' => 'password',
            'type' => 'text',
            'attributes' => [
                'label' => 'Password',
            ],
        ]);
        $this->add([
            'name' => 'login',
            'type' => 'text',
            'attributes' => [
                'label' => 'Login',
            ],
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'text',
            'attributes' => [
                'label' => 'Email',
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
