<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Model\ClothesTable;
use Admin\Model\TypeTable;
use Admin\Model\ColorTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ClothesController extends AbstractActionController
{
    private $clothesTable;
    private $typeTable;


    public function __construct(ClothesTable $clothesTable, TypeTable $typeTable,ColorTable $colorTable)
    {

        $this->clothesTable = $clothesTable;
        $this->typeTable = $typeTable;
		$this->colorTable = $colorTable;
    }

    public function indexAction()
    {
        $clothes = $this->clothesTable->fetchAllFull();
        $types = $this->typeTable->fetchAll();
		$colors = $this->colorTable->fetchAll();
        return new ViewModel(compact('clothes', 'types','colors'));
    }
}
