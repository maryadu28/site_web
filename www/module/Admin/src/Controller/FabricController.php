<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Model\Fabric;
use Admin\Form\FabricForm;
use Admin\Model\FabricTable;

class FabricController extends AbstractActionController

{
    private $table;
	
	public function onDispatch(MvcEvent $e) {
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		$response = parent::onDispatch($e);
		if (!isset($_SESSION['admin'])){
            return $this->redirect()->toRoute('log');
		}
		return $response;
		
	}

    // Add this constructor:
    public function __construct(FabricTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'fabrics' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new FabricForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $fabrics = new Fabric();
        $form->setInputFilter($fabrics->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $fabrics->exchangeArray($form->getData());
        $this->table->saveFabric($fabrics);
        return $this->redirect()->toRoute('fabric');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('fabric', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $fabric = $this->table->getFabric($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('fabric', ['action' => 'index']);
        }

        $form = new FabricForm();
        $form->bind($fabric);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($fabric->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveFabric($fabric);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('fabric', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('fabric');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteFabric($id);
            }

            // Redirect to list of clothes
            return $this->redirect()->toRoute('fabric');
        }

        return [
            'id'    => $id,
            'fabric' => $this->table->getFabric($id),
        ];
    }
}
