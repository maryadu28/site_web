<?php

namespace  Admin\Controller;

use Admin\Model\BookTable;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
// Add the following import statements at the top of the file:
use Admin\Form\BookForm;
use Admin\Model\book;

class BookController extends AbstractActionController
{
    // Add this property:
    private $table;
	
	public function onDispatch(MvcEvent $e) {
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		$response = parent::onDispatch($e);
		
		return $response;
		
	}

    // Add this constructor:
    public function __construct(BookTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'books' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new BookForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $book = new Book();
        $form->setInputFilter($book->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $book->exchangeArray($form->getData());
        $this->table->saveBook($book);
        return $this->redirect()->toRoute('book');
    }
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('book', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $book = $this->table->getBook($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('book', ['action' => 'index']);
        }

        $form = new BookForm();
        $form->bind($book);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($book->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveBook($book);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('book', ['action' => 'index']);
    }




    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('book');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deletebook($id);
            }

            // Redirect to list of books
            return $this->redirect()->toRoute('book');
        }

        return [
            'id'    => $id,
            'book' => $this->table->getbook($id),
        ];
    }
}
