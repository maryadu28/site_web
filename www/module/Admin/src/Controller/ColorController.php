<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\Color;
use Laminas\Mvc\MvcEvent;
use Admin\Form\ColorForm;
use Admin\Model\ColorTable;

class ColorController extends AbstractActionController

{
    private $table;

    public function onDispatch(MvcEvent $e)
    {

        $this->layout()->setTemplate('layout/layout-admin.phtml');

        $response = parent::onDispatch($e);
		if (!isset($_SESSION['admin'])) {
            return $this->redirect()->toRoute('log');
		}
        return $response;
    }


    // Add this constructor:
    public function __construct(ColorTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'colors' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new ColorForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $Colors = new Color();
        $form->setInputFilter($Colors->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $Colors->exchangeArray($form->getData());
        $this->table->saveColor($Colors);
        return $this->redirect()->toRoute('color');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('color', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $Color = $this->table->getColor($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('color', ['action' => 'index']);
        }

        $form = new ColorForm();
        $form->bind($Color);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($Color->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveColor($Color);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('color', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('color');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteColor($id);
            }

            // Redirect to list of clothes
            return $this->redirect()->toRoute('color');
        }

        return [
            'id'    => $id,
            'color' => $this->table->getColor($id),
        ];
    }
}
