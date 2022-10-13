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

class Shoes implements InputFilterAwareInterface
{
    public $id;
    public $shoesSize;
    public $typeShoes;
    public $color;
    public $mark;
    public $img;


    //Clé étrangères
    public $name_color;
    public $name_mark;


    // Add this property:
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id  = !empty($data['id']) ? $data['id'] : null;
        $this->shoesSize = !empty($data['shoesSize']) ? $data['shoesSize'] : null;
        $this->mark = !empty($data['mark']) ? $data['mark'] : null;
        $this->color = !empty($data['color']) ? $data['color'] : null;
        $this->typeShoes = !empty($data['typeShoes']) ? $data['typeShoes'] : null;
        $this->img = !empty($data['img']) ? $data['img'] : null;



        $this->name_color = !empty($data['name_color']) ? $data['name_color'] : null;
        $this->name_mark = !empty($data['name_mark']) ? $data['name_mark'] : null;
    }
    public function getArrayCopy()
    {
        return [
            'id'   => $this->id,
            'shoesSize' => $this->shoesSize,
            'color'  => $this->color,
            'mark' => $this->mark,
            'typeShoes'  => $this->typeShoes,
            'img' => $this->img,

            'name_color' => $this->name_color,
            'name_mark' => $this->name_mark,
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
            'name' => 'typeShoes',
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
            'name' => 'shoesSize',
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
            'name' => 'mark',
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
