<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Model\ShoesTable;
use Admin\Model\BrandTable;
use Admin\Model\ColorTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ShoesController extends AbstractActionController
{
    private $shoesTable;
    private $brandTable;


    public function __construct(ShoesTable $shoesTable, BrandTable $brandTable,ColorTable $colorTable)
    {

        $this->shoesTable = $shoesTable;
        $this->brandTable = $brandTable;
		$this->colorTable = $colorTable;
    }

    public function indexAction()
    {   
        $shoes = $this->shoesTable->fetchAllFull();
        $brands = $this->brandTable->fetchAll();
		$colors = $this->colorTable->fetchAll();
        return new ViewModel(compact('shoes', 'brands','colors'));
    }
}
