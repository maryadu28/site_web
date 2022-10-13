<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\Brand;
use Laminas\Mvc\MvcEvent;
use Admin\Form\BrandForm;
use Admin\Model\BrandTable;

class BrandController extends AbstractActionController

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
    public function __construct(BrandTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            's' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new BrandForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $brands = new Brand();
        $form->setInputFilter($brands->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $brands->exchangeArray($form->getData());
        $this->table->saveBrand($brands);
        return $this->redirect()->toRoute('brand');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('brand', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $brand = $this->table->getBrand($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('brand', ['action' => 'index']);
        }

        $form = new BrandForm();
        $form->bind($brand);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($brand->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveBrand($brand);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('brand', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('brand');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteBrand($id);
            }

            // Redirect to list of clothes
            return $this->redirect()->toRoute('brand');
        }

        return [
            'id'    => $id,
            'brand' => $this->table->getBrand($id),
        ];
    }
}
