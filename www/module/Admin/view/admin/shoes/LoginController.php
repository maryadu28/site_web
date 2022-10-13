<?php

declare(strict_types=1);

namespace Admin\Controller;

use Admin\Model\AdminTable;
use Admin\Model\GroupTable;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class LoginController extends AbstractActionController
{
	/*public function onDispatch(MvcEvent $e)
	{

		$this->layout()->setTemplate('layout/layout.phtml');

		$response = parent::onDispatch($e);

		return $response;
	}
	
*/

	public function __construct(AdminTable $adminTable)
	{
		$this->adminTable = $adminTable;
	}


	public function indexAction()
	{
		 return new ViewModel();  
	}
	
	
	public function processformAction()
	{
		//d($_POST);
		$login = $_POST['login'];
		$password = $_POST['password'];
		$admin=
	
		exit();//ne renvoie pas vers la vue 
	}
	
	
	public function logoutAction()
	{
	     session_destroy();
		 session_unset();
		 $this->redirect()->toRoute('log');
			
	}
   
}
