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

class Clothes implements InputFilterAwareInterface
{
    public $id;
    public $type;
    public $color;
    public $fabric;
    public $size;
    public $description;
    public $img;


    //Clé étrangères
    public $name_color;
    public $name_type;


    // Add this property:
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id  = !empty($data['id']) ? $data['id'] : null;
        $this->type = !empty($data['type']) ? $data['type'] : null;
        $this->color  = !empty($data['color']) ? $data['color'] : null;
        $this->fabric = !empty($data['fabric']) ? $data['fabric'] : null;
        $this->size = !empty($data['size']) ? $data['size'] : null;
        $this->description = !empty($data['description']) ? $data['description'] : null;


        $this->name_color = !empty($data['name_color']) ? $data['name_color'] : null;
        $this->name_fabric = !empty($data['name_fabric']) ? $data['name_fabric'] : null;
        $this->name_type = !empty($data['name_type']) ? $data['name_type'] : null;
        $this->name_size = !empty($data['name_size']) ? $data['name_size'] : null;
        $this->img = !empty($data['img']) ? $data['img'] : null;
    }
    public function getArrayCopy()
    {
        return [
            'id'   => $this->id,
            'type' => $this->type,
            'color'  => $this->color,
            'fabric' => $this->fabric,
            'size'  => $this->size,
            'description'  => $this->description,
            'img' => $this->img,

            'name_color' => $this->name_color,
            'name_type' => $this->name_type,
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
            'name' => 'type',
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
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'color',
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
            'name' => 'fabric',
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
            'name' => 'size',
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
                        'max' => 5,
                    ],
                ],
            ],
        ]);






        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
