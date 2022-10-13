<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Model\AccessoryTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AccessorydetailController extends AbstractActionController
{
	private $accessoryTable;

	public function __construct(AccessoryTable $accessoryTable)
	{

		$this->accessoryTable = $accessoryTable;
	}

	public function indexAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$accessory = $this->accessoryTable->getAccessory($id);
		
		return new ViewModel(compact('accessory'));
	}
}
