<?php

namespace Admin\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class ClothesForm extends Form
{
    public function __construct($types, $colors, $fabrics, $sizes)

    {
        $this->types = $types;
        $this->colors = $colors;
        $this->fabrics = $fabrics;
        $this->sizes = $sizes;




        // We will ignore the name provided to the constructor
        parent::__construct('clothes');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $options = array();
        foreach ($types as $type) {
            $options[$type->id] = $type->type;
        }

        $options_1 = array();
        foreach ($colors as $color) {
            $options_1[$color->id] = $color->color;
        }

        $options_2 = array();
        foreach ($fabrics as $fabric) {
            $options_2[$fabric->id] = $fabric->fabric;
        }

        $options_3 = array();
        foreach ($sizes as $size) {
            $options_3[$size->id] = $size->size;
        }


        $this->add([
            'name' => 'type',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Type',
                'value_options' => $options,
            ],
        ]);

        $this->add([
            'name' => 'color',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Couleur',
                'value_options' => $options_1,


            ],
        ]);
        $this->add([
            'name' => 'fabric',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Matière',
                'value_options' => $options_2,

            ],
        ]);
        $this->add([
            'name' => 'size',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Taille',
                'value_options' => $options_3,

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
            'name' => 'description',
            'type' => 'text',
            'options' => [
                'label' => 'Description',



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
