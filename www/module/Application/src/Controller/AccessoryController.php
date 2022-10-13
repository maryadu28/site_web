<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Model\AccessoryTable;
use Admin\Model\MaterialTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AccessoryController extends AbstractActionController
{
	private $accessoryTable;



	public function __construct(AccessoryTable $accessoryTable, MaterialTable $materialTable)
	{

		$this->accessoryTable = $accessoryTable;


		$this->materialTable = $materialTable;
	}

	public function indexAction()
	{
		//$accessories = $this->accessoryTable->fetchAllFull()->toArray();
		$accessories = $this->accessoryTable->fetchAllFull();
		//d($accessories);
		$materials = $this->materialTable->fetchAll();
		$accessory = $this->accessoryTable->getAccessory(9);
		
		//d($accessory);
		return new ViewModel(compact('accessories', 'materials', 'accessory'));
	}
}
