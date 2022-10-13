<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\Admin;
use Laminas\Mvc\MvcEvent;
use Admin\Form\AdminForm;
use Admin\Model\AdminTable;
use Admin\Model\GroupTable;

class AdminController extends AbstractActionController

{
    private $adminTable;
    private $groupTable;
    public function onDispatch(MvcEvent $e)
    {

        $this->layout()->setTemplate('layout/layout-admin.phtml');

        $response = parent::onDispatch($e);
        if (!isset($_SESSION['admin'])) {
            return $this->redirect()->toRoute('log');
        }
        //d($_SESSION['admin']);


        //   $admin = unserialize($_SESSION['admin']);
        //d($_SESSION['admin']);
        // d($admin->name);
        return $response;
    }
    // Add this constructor:
    public function __construct(AdminTable $adminTable, GroupTable $groupTable)
    {
        $this->adminTable = $adminTable;
        $this->groupTable = $groupTable;
    }

    public function indexAction()
    {
        $admins = $this->adminTable->fetchAllFull();
        return new ViewModel(compact('admins'));
    }

    public function addAction()
    {
        $groups = $this->groupTable->fetchAll();


        $form = new AdminForm($groups);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {

            return ['form' => $form];
        }

        $admin = new Admin();
        $form->setInputFilter($admin->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            // d($form->getMessages());
            return ['form' => $form];
        }

        $admin->exchangeArray($form->getData());
        //upload les images
        move_uploaded_file($_FILES['img']['tmp_name'], '/home/amira/www/public/img/' . $_FILES['img']['name']);
        $admin->img = $_FILES['img']['name'];
        //d($_FILES);

        $this->adminTable->saveAdmin($admin);
        return $this->redirect()->toRoute('admin');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $admin = $this->adminTable->getAdmin($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin', ['action' => 'index']);
        }
        $groups = $this->groupTable->fetchAll();
        $form = new AdminForm($groups);
        $form->bind($admin);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($admin->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }
        move_uploaded_file($_FILES['img']['tmp_name'], '/home/amira/www/public/img/' . $_FILES['img']['name']);
        $admin->img = $_FILES['img']['name'];
        try {
            $this->adminTable->saveadmin($admin);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('admin', ['action' => 'index']);
    }


    public function deleteAction()
    {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->adminTable->deleteAdmin($id);
            }

            // Redirect to list of admin
            return $this->redirect()->toRoute('admin');
        }

        return [
            'id'    => $id,

            'adminss' => $this->adminTable->getAdmin($id),
        ];
    }
}
