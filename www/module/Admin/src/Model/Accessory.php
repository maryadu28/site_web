<?php

namespace Admin\Model;
// Add the following import statements:
use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;

class Accessory implements InputFilterAwareInterface
{
    public $id;
    public $price;
    public $name;
    public $description;
    public $id_material;
    public $reference;
    public $id_color;
    public $id_brand;
    public $id_category;
    public $img;

    //Clé étrangères
    public $name_color;
    public $name_price;
    public $name_material;
    public $name_reference;
    public $name_brand;
    public $name_category;

    // Add this property:
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id  = !empty($data['id']) ? $data['id'] : null;
        $this->price = !empty($data['price']) ? $data['price'] : null;
        $this->name  = !empty($data['name']) ? $data['name'] : null;
        $this->description = !empty($data['description']) ? $data['description'] : null;
        $this->reference = !empty($data['reference']) ? $data['reference'] : null;
        $this->id_color = !empty($data['id_color']) ? $data['id_color'] : null;
        $this->id_material = !empty($data['id_material']) ? $data['id_material'] : null;
        $this->id_brand = !empty($data['id_brand']) ? $data['id_brand'] : null;
        $this->id_category = !empty($data['id_category']) ? $data['id_category'] : null;
        $this->img = !empty($data['img']) ? $data['img'] : null;



        $this->name_color = !empty($data['name_color']) ? $data['name_color'] : null;
        $this->name_brand = !empty($data['name_brand']) ? $data['name_brand'] : null;
        $this->name_category = !empty($data['name_category']) ? $data['name_category'] : null;
        $this->name_material = !empty($data['name_material']) ? $data['name_material'] : null;
    }
    public function getArrayCopy()
    {
        return [
            'id'   => $this->id,
            'price' => $this->price,
            'name'  => $this->name,
            'description'  => $this->description,
            'reference' => $this->reference,
            'id_color'  => $this->id_color,
            'id_material' => $this->id_material,
            'id_brand' => $this->id_brand,
            'id_category' => $this->id_category,
            'img' => $this->img,

            'name_color' => $this->name_color,
            'name_category' => $this->name_category,
            'name_material' => $this->name_material,
            'name_brand' => $this->name_brand,
        ];
    }
    /* Add the following methods: */

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }


        $inputFilter = new InputFilter();


        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'price',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);



        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);





        $inputFilter->add([
            'name' => 'description',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 255,
                    ],
                ],
            ],
        ]);


        $inputFilter->add([
            'name' => 'reference',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'id_color',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);


        $inputFilter->add([
            'name' => 'id_material',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);


        $inputFilter->add([
            'name' => 'id_brand',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);



        $inputFilter->add([
            'name' => 'id_category',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);






        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
