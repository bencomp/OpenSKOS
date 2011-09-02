<?php

class Dashboard_LoginController extends Zend_Controller_Action {
	
	public function init() {
		if (Zend_Auth::getInstance ()->hasIdentity ()) {
			$this->_helper->redirector ( 'index', 'index' );
		}
	}
	
	public function indexAction() {
		$this->view->form = Dashboard_Forms_Login::getInstance ()
			->setAction ( $this->getFrontController ()->getRouter ()->assemble ( array ('controller'=>'login', 'action' => 'authenticate' ) ) );
	}
	
	public function authenticateAction() {
		$form = Dashboard_Forms_Login::getInstance ();
		
		$request = $this->getRequest ();
		if (! $this->getRequest ()->isPost ()) {
			$this->_helper->redirector ( 'index' );
		}
		
		if (! $form->isValid ( $this->getRequest ()->getPost () )) {
			return $this->_forward ( 'index' );
		}
		
		$tenant = $form->getValue ( 'tenant' );
		$username = $form->getValue ( 'username' );
		$password = $form->getValue ( 'password' );
		$login = new Dashboard_Models_Login ();
		$login->setData ($tenant, $username, $password );
		if ($login->isValid ()) {
			$this->getHelper ( 'FlashMessenger' )->addMessage ( 'Succesfully logged in' );
			$this->_helper->redirector ( 'index', 'index' );
		} else {
    		$this->getHelper('FlashMessenger')->setNamespace('error')->addMessage(array_pop($login->getMessages()));
    		$this->_helper->redirector('index');
		}
	}
}