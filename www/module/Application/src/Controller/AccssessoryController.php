<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Model\MaterialTable;
use Admin\Model\AccessoryTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AccessoryController extends AbstractActionController
{
	private $accessoryTable;
	
	public function __construct(AccessoryTable $accessoryTable,MaterialTable $materialTable) {
		
		$this->accessoryTable = $accessoryTable;
		$this->materialTable = $materialTable;
	}
	
    public function indexAction()
    {
		$accessories = $this->accessoryTable->fetchAllFull();
		$materials = $this->accessoryTable->fetchAll();	
        return new ViewModel(compact('accessories','materials'));
    }
}
