<?php



namespace Admin\Controller;

use Admin\Model\AdminTable;
use Admin\Model\GroupTable;
use Admin\Form\AdminForm;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class LoginController extends AbstractActionController
{
	public function onDispatch(MvcEvent $e)
	{

		$this->layout()->setTemplate('layout/layout.phtml');

		$response = parent::onDispatch($e);

		return $response;
	}


	public function __construct(AdminTable $adminTable, GroupTable $groupTable)
	{
		$this->adminTable = $adminTable;

		$this->groupTable = $groupTable;
	}

	public function indexAction()
	{
		return new ViewModel([]);
		// echo $password;
		//$pass="tata";
		//echo md5($pass);
		//d($admin->id);
		//d($test->id);
		//d($_POST);
		//on stock l'objet admin dans la session, on utilisant la fonction serialize()
	}
	public function processformAction()
	{
		$login = $_POST['login'];
		$password = md5($_POST['password']);

		if ($admin = $this->adminTable->getLogin($login, $password)) {
			$test = serialize($admin);
			$_SESSION['admin'] = $test;

			return $this->redirect()->toRoute('admin');
		} else {
			//echo "les identifiants sont  invalides ";
			return $this->redirect()->toRoute('log');
		}
	}
	//exit();//ne renvoie pas vers la vue 


	public function logoutAction()
	{
		session_destroy();
		session_unset();
		$this->redirect()->toRoute('log');
		//exit();
	}
}
