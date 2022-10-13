<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\Size;
use Laminas\Mvc\MvcEvent;
use Admin\Form\SizeForm;
use Admin\Model\SizeTable;

class SizeController extends AbstractActionController

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
    public function __construct(SizeTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'sizes' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new SizeForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $sizes = new Size();
        $form->setInputFilter($sizes->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $sizes->exchangeArray($form->getData());
        $this->table->saveSize($sizes);
        return $this->redirect()->toRoute('size');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('size', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $Size = $this->table->getSize($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('size', ['action' => 'index']);
        }

        $form = new SizeForm();
        $form->bind($Size);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($Size->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveSize($Size);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('size', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('size');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteSize($id);
            }

            // Redirect to list of clothes
            return $this->redirect()->toRoute('size');
        }

        return [
            'id'    => $id,
            'size' => $this->table->getSize($id),
        ];
    }
}
