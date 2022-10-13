<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Model\Accessory;
use Admin\Form\AccessoryForm;
use Admin\Model\AccessoryTable;
use Admin\Model\CategoryTable;
use Admin\Model\ColorTable;
use Admin\Model\MaterialTable;
use Admin\Model\BrandTable;


class AccessoryController extends AbstractActionController

{
    private $accessoryTable;
    private $categoryTable;
	private $colorTable;
	private $materialTable;
	private $brandTable;
	
	
	public function onDispatch(MvcEvent $e) {
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		$response = parent::onDispatch($e);
		if (!isset($_SESSION['admin'])) {
            return $this->redirect()->toRoute('log');
		}
		return $response;
		
	}

    // Add this constructor:
    public function __construct(AccessoryTable $accessoryTable,CategoryTable $categoryTable,ColorTable $colorTable ,MaterialTable $materialTable ,BrandTable $brandTable)
		
    {
        $this->accessoryTable = $accessoryTable;
		$this->categoryTable = $categoryTable;
		$this->colorTable= $colorTable;
		$this->materialTable= $materialTable;
		$this->brandTable = $brandTable;
		
    }

    public function indexAction()
    {
		$accessorys = $this->accessoryTable ->fetchAllFull();
//		$Accessory = $this->table->fetchAllFullcategory();
	
		
        return new ViewModel(compact('accessorys'));
		
    }

    public function addAction()
    {
        $categorys = $this->categoryTable->fetchAll();
		$colors = $this->colorTable->fetchAll();
		$materials = $this->materialTable->fetchAll();
		$brands = $this->brandTable->fetchAll();
			

		
		
		$form = new AccessoryForm( $categorys, $colors, $materials, $brands);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }
		
        $accessory = new Accessory();
        $form->setInputFilter($accessory->getInputFilter());
        $form->setData($request->getPost());
		//d($request->getPost());

        if (!$form->isValid()) {
			//d($form->getMessages());
            return ['form' => $form];
			//return['error'=>1,'from'=>$gform];
        }
		
        $accessory->exchangeArray($form->getData());
		//d($accessory);
		//Déplacement de l'image 
		move_uploaded_file($_FILES['img']['tmp_name'],'/home/amira/www/public/img/'.$_FILES['img']['name']);
		$accessory->img=$_FILES['img']['name'];
		//d($_FILES);
        $this->accessoryTable->saveAccessory($accessory);
        return $this->redirect()->toRoute('accessory');
    }

    public function editAction()
    {
		$categorys = $this->categoryTable->fetchAll();
		$colors = $this->colorTable->fetchAll();
		$materials = $this->materialTable->fetchAll();
		$brands = $this->brandTable->fetchAll();
			
		
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('accessory', ['action' => 'add']);
        }

        // Retrieve the Accessory with the specified id. Doing so raises
        // an exception if the Accessory is not found, which should result
        // in redirecting to the landing page.
        try {
            $accessory = $this->accessoryTable->getAccessory($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('accessory', ['action' => 'index']);
        }

		
        $form = new AccessoryForm($categorys,$colors, $materials, $brands);
		
        $form->bind($accessory);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }
	

        $form->setInputFilter($accessory->getInputFilter());
        $form->setData($request->getPost());
		//d($request->getPost());

        if (!$form->isValid()) {
		//	d($form->getMessages());
            return $viewData;
			
        }
        move_uploaded_file($_FILES['img']['tmp_name'],'/home/amira/www/public/img/'.$_FILES['img']['name']);
		$accessory->img=$_FILES['img']['name'];
		
        try {
            $this->accessoryTable->saveAccessory($accessory);
        } catch (\Exception $e) {
        }

        // Redirect to Accessory list
        return $this->redirect()->toRoute('accessory', ['action' => 'index']);
    }


    public function deleteAction()
		
    {
		$accessorys = $this->accessoryTable ->fetchAllFull();
		
		
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('accessory');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->accessoryTable->deleteAccessory($id);
            }

            // Redirect to list of Accessory
            return $this->redirect()->toRoute('accessory');
        }

        return [
            'id'    => $id,
            'accessory' => $this->accessoryTable->getAccessory($id),
        ];
    }
}
