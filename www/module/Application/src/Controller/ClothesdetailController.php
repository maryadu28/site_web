<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Model\ClothesTable;
use Admin\Model\TypeTable;
use Admin\Model\ColorTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ClothesdetailController extends AbstractActionController
{
    private $clothesTable;
    private $typeTable;


    public function __construct(ClothesTable $clothesTable, TypeTable $typeTable, ColorTable $colorTable)
    {

        $this->clothesTable = $clothesTable;
        $this->typeTable = $typeTable;
        $this->colorTable = $colorTable;
    }

    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $clothe = $this->clothesTable->getClothes($id);
        $types = $this->typeTable->fetchAll();
        $colors = $this->colorTable->fetchAll();
        return new ViewModel(compact('clothe', 'types', 'colors'));
    }
}
