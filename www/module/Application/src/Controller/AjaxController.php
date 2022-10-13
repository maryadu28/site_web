<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Model\AccessoryTable;
use Admin\Model\BrandTable;
use Admin\Model\ClothesTable;
use Admin\Model\ShoesTable;
use Admin\Model\TypeTable;
use Admin\Model\ColorTable;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class AjaxController extends AbstractActionController
{
	// layout pour ajax 
	public function onDispatch(MvcEvent $e)
	{

		$this->layout()->setTemplate('layout/layout-ajax.phtml');

		$response = parent::onDispatch($e);

		return $response;
	}


	//Add this constructor

	/*public function __construct(AccessoryTable $accessoryTable)
	{

		$this->accessoryTable = $accessoryTable;


	}
*/

	public function __construct(ClothesTable $clothesTable, AccessoryTable $accessoryTable, ShoesTable $shoesTable)
	{


		$this->clothesTable = $clothesTable;
		$this->accessoryTable = $accessoryTable;
		$this->accessoryTable = $accessoryTable;
		$this->shoesTable = $shoesTable;
		//$this->colorTable = $colorTable;


	}

	public function getclothesAction()
	{
		$clothes = $this->clothesTable->fetchAllFull();


		return new ViewModel(compact('clothes'));
	}

	/*
	public function getshoesAction()

	{
		$shoes = $this->shoesTable->fetchAllFull();
		//d($shoes);
		return new ViewModel(compact('shoes'));
	}
 */

	public function getaccessoriesAction()
	{
		//$accessories = $this->accessoryTable->fetchAllFull();
		$accessories = $this->accessoryTable->fetchOne();

		return new ViewModel(compact('accessories'));
	}


	public function getoneaccessoryAction()
	{

		$compteur = $_POST['compteur'];
		//d($compteur);

		//pour eviter l'erreur de violation d'acces de la bd lorsque le compteur est 0 

		if (empty($compteur)) {

			exit();
		} else {

			$accessories = $this->accessoryTable->fetchAllFullOne($compteur);
		}

		return new ViewModel(compact('accessories'));
	}
}
