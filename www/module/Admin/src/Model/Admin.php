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

class Admin implements InputFilterAwareInterface

{
    public $id;
    public $name;
    public $id_group;
    public $img;
    public $password;
    public $login;
    public $email;
    private $inputFilter;


    //Clé étrangères
    public $name_group;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->id_group = !empty($data['id_group']) ? $data['id_group'] : null;
        $this->img = !empty($data['img']) ? $data['img'] : null;
        $this->name_group = !empty($data['name_group']) ? $data['name_group'] : null;
        $this->password = !empty($data['password']) ? $data['password'] : null;
        $this->login = !empty($data['login']) ? $data['login'] : null;
        $this->email = !empty($data['email']) ? $data['email'] : null;
    }
    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'name' => $this->name,
            'id_group'  => $this->id_group,
            'name_group' => $this->name_group,
            'img' => $this->img,
            'password' => $this->password,
            'login' => $this->login,
            'email' => $this->email


        ];
    }

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
            'name' => 'id_group',
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
