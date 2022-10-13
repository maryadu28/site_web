<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\Type;
use Laminas\Mvc\MvcEvent;
use Admin\Form\TypeForm;
use Admin\Model\TypeTable;

class TypeController extends AbstractActionController

{
    private $table;
	
	public function onDispatch(MvcEvent $e) {
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		$response = parent::onDispatch($e);
		if(!isset($_SESSION['admin'])){
            return $this->redirect()->toRoute('log');
		}
		return $response;
		
	}

    // Add this constructor:
    public function __construct(TypeTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'types' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new TypeForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $types = new Type();
        $form->setInputFilter($types->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $types->exchangeArray($form->getData());
        $this->table->saveType($types);
        return $this->redirect()->toRoute('type');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('type', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $type = $this->table->getType($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('type', ['action' => 'index']);
        }

        $form = new TypeForm();
        $form->bind($type);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($type->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveType($type);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('type', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('type');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteType($id);
            }

            // Redirect to list of clothes
            return $this->redirect()->toRoute('type');
        }

        return [
            'id'    => $id,
            'type' => $this->table->getType($id),
        ];
    }
}
