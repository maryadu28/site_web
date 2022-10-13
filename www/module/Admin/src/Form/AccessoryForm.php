<?php

namespace Admin\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class AccessoryForm extends Form
{
	public function __construct( $categorys, $colors, $materials, $brands)

	{
		
		$this->categorys = $categorys;
		$this->colors = $colors;
		$this->brands = $brands;
		$this->materials = $materials;




		// We will ignore the name provided to the constructor
		parent::__construct('accessory');

		$this->add([
			'name' => 'id',
			'type' => 'hidden',
		]);

		$options = array();
		foreach ($categorys as $category) {
			$options[$category->id] = $category->category;
		}

		$options_1 = array();
		foreach ($colors as $color) {
			$options_1[$color->id] = $color->color;
		}

		$options_2 = array();
		foreach ($brands as $brand) {
			//d($brand);
			$options_2[$brand->id] = $brand->brand;
		}

		$options_3 = array();
		foreach ($materials as $material) {
			$options_3[$material->id] = $material->material;
		}



		$this->add([
			'name' => 'price',
			'type' => Element\Select::class,
			'options' => [
				'label' => 'Price',

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
			'name' => 'id_category',
			'type' => Element\Select::class,
			'options' => [
				'label' => 'Catégorie',
				'value_options' => $options,


			],
		]);


		$this->add([
			'name' => 'id_color',
			'type' => Element\Select::class,
			'options' => [
				'label' => 'Couleur',
				'value_options' => $options_1,


			],
		]);

		$this->add([
			'name' => 'id_material',
			'type' => Element\Select::class,
			'options' => [
				'label' => 'Matière',
				'value_options' => $options_3,


			],
		]);


		$this->add([
			'name' => 'id_brand',
			'type' => Element\Select::class,
			'options' => [
				'label' => 'Marque',
				'value_options' => $options_2,


			],
		]);




		$this->add([
			'name' => 'name',
			'type' =>'text',
			'options' => [
				'label' => 'Nom',
				



			],
		]);

		$this->add([
			'name' => 'reference',
			'type' =>'text',
			'options' => [
				'label' => 'Reference',
				


			],
		]);

		$this->add([
			'name' => 'price',
			'type' => 'text',
			'options' => [
				'label' => 'Prix',
				
				

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
