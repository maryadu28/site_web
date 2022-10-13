<?php

namespace Admin\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class ShoesForm extends Form
{
	public function __construct($colors, $brands)

	{


		$this->colors = $colors;
		$this->brands = $brands;





		// We will ignore the name provided to the constructor
		parent::__construct('shoes');

		$this->add([
			'name' => 'id',
			'type' => 'hidden',
		]);


		$options_1 = array();
		foreach ($colors as $color) {
			$options_1[$color->id] = $color->color;
		}

		$options_2 = array();
		foreach ($brands as $brand) {
			//d($brand);
			$options_2[$brand->id] = $brand->brand;
		}




		$this->add([
			'name' => 'shoesSize',
			'type' => 'text',
			'options' => [
				'label' => 'Pointure',

			],
		]);

		$this->add([
			'name' => 'typeShoes',
			'type' => 'text',
			'options' => [
				'label' => 'Type',
			],
		]);



		$this->add([
			'name' =>'img',
			'type' => 'file',
			'options' => [
				'label' => 'Image',

			],
		]);


		$this->add([
			'name' => 'brand',
			'type' => Element\Select::class,
			'options' => [
				'label' => 'Marque',
				'value_options' => $options_2,


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
			'name' => 'submit',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Go',
				'id'    => 'submitbutton',


			],
		]);
	}
}
