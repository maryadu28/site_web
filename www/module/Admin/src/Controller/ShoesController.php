<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Model\Shoes;
use Admin\Form\ShoesForm;
use Admin\Model\ShoesTable;
use Admin\Model\BrandTable;
use Admin\Model\ColorTable;



class ShoesController extends AbstractActionController

{
    private $shoesTable;
    private $colorTable;
    private $brandTable;


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
    public function __construct(ShoesTable $shoesTable, ColorTable $colorTable, BrandTable $brandTable)

    {
        $this->shoesTable = $shoesTable;
        $this->colorTable = $colorTable;
        $this->brandTable = $brandTable;
    }

    public function indexAction()
    {
        $shoess = $this->shoesTable->fetchAllFull();
        //		$shoes = $this->table->fetchAllFullcategory();


        return new ViewModel(compact('shoess'));
    }

    public function addAction()
    {
        $colors = $this->colorTable->fetchAll();
        $brands = $this->brandTable->fetchAll();

        $form = new ShoesForm($colors, $brands);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $shoes = new Shoes();
        $form->setInputFilter($shoes->getInputFilter());
        $form->setData($request->getPost());
        // d($request->getPost());

        if (!$form->isValid()) {
            //  d($form->getMessages());
            return ['form' => $form];
            //return['error'=>1,'from'=>$gform];
        }

        $shoes->exchangeArray($form->getData());
        //d($shoes);
        //Déplacement de l'image 
        move_uploaded_file($_FILES['img']['tmp_name'], '/home/amira/www/public/img/' . $_FILES['img']['name']);
        $shoes->img = $_FILES['img']['name'];
        //d($_FILES);
        $this->shoesTable->saveShoes($shoes);
        return $this->redirect()->toRoute('shoes');
    }

    public function editAction()
    {
        $colors = $this->colorTable->fetchAll();
        $brands = $this->brandTable->fetchAll();


        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('shoes', ['action' => 'add']);
        }

        // Retrieve the shoes with the specified id. Doing so raises
        // an exception if the shoes is not found, which should result
        // in redirecting to the landing page.
        try {
            $shoes = $this->shoesTable->getShoes($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('shoes', ['action' => 'index']);
        }


        $form = new ShoesForm($colors, $brands);

        $form->bind($shoes);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }


        $form->setInputFilter($shoes->getInputFilter());
        $form->setData($request->getPost());
        //d($request->getPost());

        if (!$form->isValid()) {
            //	d($form->getMessages());
            return $viewData;
        }
        move_uploaded_file($_FILES['img']['tmp_name'], '/home/amira/www/public/img/' . $_FILES['img']['name']);
        $shoes->img = $_FILES['img']['name'];

        try {
            $this->shoesTable->saveshoes($shoes);
        } catch (\Exception $e) {
        }

        // Redirect to shoes list
        return $this->redirect()->toRoute('shoes', ['action' => 'index']);
    }


    public function deleteAction()

    {
        $shoess = $this->shoesTable->fetchAllFull();


        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('shoes');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->shoesTable->deleteshoes($id);
            }

            // Redirect to list of shoes
            return $this->redirect()->toRoute('shoes');
        }

        return [
            'id'    => $id,
            'shoes' => $this->shoesTable->getshoes($id),
        ];
    }
}
