<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\Group;
use Laminas\Mvc\MvcEvent;
use Admin\Form\GroupForm;
use Admin\Model\GroupTable;

class GroupController extends AbstractActionController

{
    private $groupTable;
	public function onDispatch(MvcEvent $e) {
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		$response = parent::onDispatch($e);
		if (!isset($_SESSION['admin'])){
            return $this->redirect()->toRoute('log');
		}
		return $response;
		
	}

    // Add this constructor:
	
    public function __construct(GroupTable $groupTable)
    {
        $this->groupTable = $groupTable;
    }

    public function indexAction()
    {
        return new ViewModel([
            'groups' => $this->groupTable->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new GroupForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $group = new Group();
        $form->setInputFilter($group->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $group->exchangeArray($form->getData());
        $this->groupTable->saveGroup($group);
        return $this->redirect()->toRoute('group');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('group', ['action' => 'add']);
        }

        // Retrieve the clothes with the specified id. Doing so raises
        // an exception if the clothes is not found, which should result
        // in redirecting to the landing page.
        try {
            $group = $this->groupTable->getGroup($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('group', ['action' => 'index']);
        }

        $form = new GroupForm();
        $form->bind($group);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($group->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->groupTable->saveGroup($group);
        } catch (\Exception $e) {
        }

        // Redirect to clothes list
        return $this->redirect()->toRoute('group', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('group');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->groupTable->deleteGroup($id);
            }

            // Redirect to list of clothes
            return $this->redirect()->toRoute('group');
        }

        return [
            'id'    => $id,
            'group' => $this->groupTable->getGroup($id),
        ];
    }
}
