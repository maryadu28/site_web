<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\Clothes;
use Laminas\Mvc\MvcEvent;
use Admin\Form\ClothesForm;
use Admin\Model\ClothesTable;
use Admin\Model\TypeTable;
use Admin\Model\ColorTable;
use Admin\Model\FabricTable;
use Admin\Model\SizeTable;


class ClothesController extends AbstractActionController

{
    private $table;
    private $typeTable;
    private $colorTable;
    private $fabricTable;
    private $sizeTable;
    
	public function onDispatch(MvcEvent $e) {
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		$response = parent::onDispatch($e);
		
		return $response;
		
	}

    // Add this constructor:
    public function __construct(ClothesTable $table, TypeTable $typeTable, ColorTable $colorTable, FabricTable $fabricTable, SizeTable $sizeTable)

    {
        $this->table = $table;
        $this->typeTable = $typeTable;
        $this->colorTable = $colorTable;
        $this->fabricTable = $fabricTable;
        $this->sizeTable = $sizeTable;
    }

    public function indexAction()
    {
        $clothes = $this->table->fetchAllFull();
        //		$clothes = $this->table->fetchAllFullType();


        return new ViewModel(compact('clothes'));
    }

    public function addAction()
    {
        $types = $this->typeTable->fetchAll();
        $colors = $this->colorTable->fetchAll();
        $fabrics = $this->fabricTable->fetchAll();
        $sizes = $this->sizeTable->fetchAll();


        $form = new ClothesForm($types, $colors, $fabrics, $sizes);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $clothes = new Clothes();
        $form->setInputFilter($clothes->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }


        $clothes->exchangeArray($form->getData());
        move_uploaded_file($_FILES['img']['tmp_name'], '/home/amira/www/public/img/' . $_FILES['img']['name']);
        $clothes->img = $_FILES['img']['name'];


        $this->table->saveClothes($clothes);
        return $this->redirect()->toRoute('clothes');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('clothes', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $clothe = $this->table->getClothes($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('clothes', ['action' => 'index']);
        }

        $types = $this->typeTable->fetchAll();
        $colors = $this->colorTable->fetchAll();
        $fabrics = $this->fabricTable->fetchAll();
        $sizes = $this->sizeTable->fetchAll();

        $form = new ClothesForm($types, $colors, $fabrics, $sizes);

        $form->bind($clothe);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        move_uploaded_file($_FILES['img']['tmp_name'], '/home/amira/www/public/img/' . $_FILES['img']['name']);
        $clothe->img = $_FILES['img']['name'];

        $form->setInputFilter($clothe->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }


        //d($_FILES);
        try {
            $this->table->saveClothes($clothe);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('clothes', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('clothes');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteClothes($id);
            }

            // Redirect to list of clothes
            return $this->redirect()->toRoute('clothes');
        }

        return [
            'id'    => $id,
            'clothe' => $this->table->getClothes($id),
        ];
    }
}
