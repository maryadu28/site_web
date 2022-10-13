<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\Category;
use Laminas\Mvc\MvcEvent;
use Admin\Form\CategoryForm;
use Admin\Model\CategoryTable;

class CategoryController extends AbstractActionController

{
    private $table;
	public function onDispatch(MvcEvent $e) {
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		$response = parent::onDispatch($e);
		if (!isset($_SESSION['admin'])) {
            return $this->redirect()->toRoute('log');
		}
		return $response;
		
	}

    // Add this constructor:
    public function __construct(CategoryTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'categorys' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new CategoryForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $categorys = new Category();
        $form->setInputFilter($categorys->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $categorys->exchangeArray($form->getData());
        $this->table->saveCategory($categorys);
        return $this->redirect()->toRoute('category');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('category', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $category = $this->table->getCategory($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('category', ['action' => 'index']);
        }

        $form = new CategoryForm();
        $form->bind($category);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($category->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveCategory($category);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('category', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteCategory($id);
            }

            // Redirect to list of clothes
            return $this->redirect()->toRoute('category');
        }

        return [
            'id'    => $id,
            'category' => $this->table->getCategory($id),
        ];
    }
}
