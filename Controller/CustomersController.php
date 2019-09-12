<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Customers Controller
 *
 * @property Customer $Customer
 */
class CustomersController extends AppController {
	
	public $components = array('Paypal', 'ProStore');
	
	function beforeFilter() {
	    parent::beforeFilter();
		$this->Auth->allow(array('index', 'edit', 'login', 'logout', 'register', 'reset', 'checkout', 'track_order', 'getLocation', 'test')); 
		
		$_customerAllowed = array('login', 'register', 'reset', 'checkout', 'track_order', 'getLocation', 'test');
		if (!$this->Session->read('Auth.User.id') && !$this->Session->read('Customer') && !in_array($this->request->params['action'], $_customerAllowed)) {
			$this->Session->setFlash(__('You need to login.'), 'Flash/admin_info');
			$this->redirect(array('controller' => 'customers', 'action' => 'login'));
		}
		/*
		if ($this->Session->read('Customer') == 1  && $this->request->params['action'] != 'login') {
			$this->Session->setFlash(__('You do not have a registered account with us.'), 'Flash/admin_info');
			$this->redirect(array('controller' => 'customers', 'action' => 'register'));
		}
		*/
	}


	public function test() {
		//pr($this->ProStore->getProductOption(24040));
		//pr($this->ProStore->getCustomer(141681));
		if ($this->request->is('post') || $this->request->is('put')) {
			$order = $this->ProStore->getOrder(array('oID' => $this->request->data['Customer']['orderID'], 'cEmail' => $this->request->data['Customer']['cEmail']));
			if ($order != 'false') {
				$this->sendMail(
					$this->request->data['Customer']['cEmail'],
					'New Order #' . $oID,
					'order-new',
					$order
				);
				$this->theme = 'LG1';
				$this->set('data', $order);
				$this->render('/Emails/html/order-new', '/Emails/html/default');
			}
		}
		$this->render('login_small');
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('balance', $this->Customer->getBalance($this->Session->read('Customer')));
	}

/**
 * edit method
 *
 * @return void
 */
	public function edit() {
		$this->Customer->id = $this->Session->read('Customer');
		if (!$this->Customer->exists()) {
			$this->redirect(array('controller' => 'customers', 'action' => 'logout'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (empty($this->request->data['Customer']['password'])) {
				$this->Customer->validator()->remove('password');
				unset($this->request->data['Customer']['password']);
			} else {
				$this->request->data['User']['password'] = $this->request->data['Customer']['password'];
			}
			$this->request->data['User']['username'] = $this->request->data['Customer']['email'];
			$this->request->data['User']['email'] = $this->request->data['Customer']['email'];
			if ($this->Customer->save($this->request->data)) {
				$this->Customer->saveAll($this->request->data);
				unset($this->request->data['Customer']['password']);
				$this->Session->setFlash(__('Your details have been updated.'), 'Flash/admin_success');
			} else {
				$this->Session->setFlash(__('There was an error with the data you entered. Please, try again.'), 'Flash/admin_error');
			}
		} else {
			$this->request->data = $this->Customer->read();
		}
		$countries = $this->Customer->Country->find('list');
		$this->set(compact('countries'));
	}


/**
 * login method
 *
 * @return void
 */
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				if ($this->Auth->user('user_status_id') == 1) {
					$this->Customer->User->id = $this->Session->read('Auth.User.id');
					$this->Customer->User->saveField('last_login', date('Y-m-d H:i:s'));
					$this->Session->delete('Message.auth');
					$this->Session->write('Customer', $this->getID($this->Session->read('Auth.User.id')));
					if ($this->Session->check('Cart')) {
						$this->redirect(array('controller' => 'customers', 'action' => 'checkout'));
					} else {
						$this->redirect(array('controller' => 'customers', 'action' => 'index'));
					}
				} else{
					$this->Session->setFlash('Your account is not active.', 'Flash/admin_info');
					$this->redirect($this->Auth->logout());
				}
			} else {
				$this->Session->setFlash(__('Incorrect Username and/or Password.'), 'Flash/admin_error');
			}
		}
		$this->Customer->id = $this->Session->read('Customer');
		if ($this->Customer->exists()) {
			$this->redirect(array('controller' => 'customers', 'action' => 'index'));
		}
	}
	
	function logout(){
		$this->Auth->logout();
		$this->Session->delete('Customer');
		$this->Session->delete('Cart');
		$this->Session->delete('Discount');
		$this->redirect('/');
	}


/**
 * register method
 *
 * @return void
 */
	public function register() {
		if ($this->request->is('post')) {
			if ($this->request->data['Customer']['checkout'] == 'register') {
				$this->request->data['User']['username'] = $this->request->data['Customer']['email'];
				$this->request->data['User']['email'] = $this->request->data['Customer']['email'];
				$this->request->data['User']['password'] = $this->request->data['Customer']['password'];
				$this->request->data['User']['user_status_id'] = 1;
				//unset($this->request->data['Customer']['password']);
				$this->Customer->unvalidate();
				$this->Customer->create();
				if ($this->Customer->saveAll($this->request->data)) {
					$this->Session->write('Customer', $this->Customer->id);
					if ($this->Session->check('Cart')) {
						$this->redirect(array('controller' => 'customers', 'action' => 'checkout'));
					} else {
						$this->redirect(array('controller' => 'customers', 'action' => 'index'));
					}
				} else {
					$this->Session->setFlash(__('Your details could not be saved. Please, try again.'), 'Flash/admin_error');
				}
			} else if ($this->request->data['Customer']['checkout'] == 'guest') {
				$this->Session->write('Customer', 1);
				$this->redirect(array('controller' => 'customers', 'action' => 'checkout'));
			} else {
				$this->Session->setFlash(__('Please include some details.'), 'Flash/admin_error');
			}
		}
		$this->Customer->addvalidation();
	}

/**
 * reset method
 *
 * @param string $id
 * @return void
 */
	public function reset() {
		if ($this->request->is('post')) {
			$c = $this->Customer->User->findByEmail($this->request->data['User']['email']);
			if ($c) {
				$new_password = $this->generatePassword($c['User']['id'], 10, 6);
				$email = new CakeEmail('default');
				$email->config(array('from' => array(Configure::read('MySite.send_email') => Configure::read('MySite.send_from'))));
				$email->helpers(array('Html'));
				$email->subject(Configure::read('MySite.site_name') . ' Password Reset');
				$email->template('newpassword');
				$email->emailFormat('both');
				$email->to($c['User']['email']);
				$email->viewVars(array(
					'site_name' => Configure::read('MySite.site_name'),
					'new_password' => $new_password,
					'c' => $c
				));
				if ($email->send()) {
					$this->Session->setFlash(__('Password reset successfully. You have been emailed your new password.'), 'Flash/admin_success');
				} else {
					$this->Session->setFlash(__('Password was not reset.'), 'Flash/admin_error');
				}
			} else {
				$this->Session->setFlash(__('Your email address was not found.'), 'Flash/admin_error');
			}
		} 
	}

/**
 * checkout method
 *
 * @return void
 */	
	public function checkout() {
		$this->set('title_for_layout', __('Checkout'));
		$cart = $this->Session->read('Cart');
		$this->Customer->validateCreditCard();
		$items = $this->_getItemsInCart();

		// if not items in cart - redirect to cart page, no checkout allowed
		if (empty($items)) {
			$this->redirect(array('controller' => 'cart', 'action' => 'index'));
		}

		// if customer session not active - redirect to login/guest selection
		/* Disabled for now
		if (!$this->Session->read('Customer')) {
			$this->redirect(array('controller' => 'customers', 'action' => 'login'));
		}
		*/

		// post from the checkout form
		if ($this->request->is('post') || $this->request->is('put')) {

			$this->Customer->remvaliduserstuff();
			$this->Customer->id = $this->Session->read('Customer');
			if (!isset($this->request->data['Customer']['cEmail'])) {
				$this->Customer->remvaliduseremail();
			}

			$this->Customer->set($this->request->data);

			// validate customer information entered is correct
			if ($this->Customer->validates()) {

				if ($this->Session->read('Customer') == 1) {
					$this->Customer->unBindModel(array('belongsTo' => array('User')));
					unset($this->request->data['Customer']['id']);
					$this->Session->delete('Customer');
				} 

				$data = array();

				if (empty($this->request->data['Customer']['id'])){ 
					// update or post new customer to main database
					$this->request->data['Customer']['cFirstSessionID'] = $this->Session->id();
					$this->request->data['Customer']['cIP'] = $_SERVER['REMOTE_ADDR'];

					$this->request->data['Customer']['id'] = $this->ProStore->saveCustomer($this->request->data['Customer']);
					$this->Customer->save($this->request->data['Customer']);
				}

				if ($this->request->data['Customer']['id']) {
					$this->Session->write('Customer', $this->request->data['Customer']['id']);
					//$this->Customer->save($this->request->data['Customer']);

					$oID = $this->ProStore->saveOrder($this->request->data, $items, $cart, Configure::read('MySite.portal'), $this->Session->id());

					if (!empty($oID)) {
						$this->log('Order created '.$oID, 'prostore');
						$this->Session->setFlash(__('Your order #' . $oID . ' has been created.'), 'Flash/success', array(), 'order');
						$l = $this->ProStore->getLocality($this->request->data['Customer']['bLocalityID']);
						$order = $this->ProStore->getOrder(array('oID' => $oID, 'cEmail' => $this->request->data['Customer']['cEmail']));

						$this->sendMail(
							array($this->request->data['Customer']['cEmail'] => $this->request->data['Customer']['cFirstName'].' '.$this->request->data['Customer']['cLastName']),
							'New Order #' . $oID,
							'order-new',
							array_merge(array('Customer' => $this->request->data['Customer']), $order)
						);

						// remove cart from database
						$this->loadModel('Cart');
						$this->Cart->removeCart($this->Session->id());
						$this->Session->delete('Cart');

						$this->loadModel('Order');
						$this->Order->saveAll($order);

						// process payment - add more payment methods when and as required
						if (isset($this->request->data['Customer']['paymentmethod']) && $this->request->data['Customer']['paymentmethod'] == 'paypal') {
							$this->Paypal->amount = $order['Order']['oOrderTotal'];
							$this->Paypal->returnUrl = Router::url(array('controller' => 'orders', 'action' => 'complete_paypal', $oID), true);
							$this->Paypal->cancelUrl = Router::url(array('controller' => 'orders', 'action' => 'view', $oID), true);
							$this->Paypal->itemName = 'Order #'.$oID;
							$this->Paypal->quantity = 1;
							$this->Paypal->customerFirstName = $this->request->data['Customer']['cFirstName'];
							$this->Paypal->customerLastName = $this->request->data['Customer']['cLastName'];
							$this->Paypal->customerEmail = $this->request->data['Customer']['cEmail'];
							$this->Paypal->billingAddress1 = $this->request->data['Customer']['bAddress1'];
							$this->Paypal->billingAddress2 = $this->request->data['Customer']['bAddress2'];
							$this->Paypal->billingCity = $l['Locality']['locality'];
							$this->Paypal->billingState = $l['Locality']['state'];
							$this->Paypal->billingCountryCode = 200; // default for Australia
							$this->Paypal->billingZip = $l['Locality']['postCode'];
							$this->Paypal->expressCheckout();
						} 

						if (isset($this->request->data['Customer']['paymentmethod']) && $this->request->data['Customer']['paymentmethod'] == 'paypaldirect') {
							$this->Paypal->amount = $order['Order']['oOrderTotal'];
							$this->Paypal->returnUrl = Router::url(array('controller' => 'orders', 'action' => 'view', $oID), true);
							$this->Paypal->cancelUrl = Router::url(array('controller' => 'orders', 'action' => 'view', $oID), true);
							$this->Paypal->itemName = 'Order #'.$oID;
							$this->Paypal->quantity = 1;
							$this->Paypal->customerFirstName = $this->request->data['Customer']['cFirstName'];
							$this->Paypal->customerLastName = $this->request->data['Customer']['cLastName'];
							$this->Paypal->customerEmail = $this->request->data['Customer']['cEmail'];
							$this->Paypal->billingAddress1 = $this->request->data['Customer']['bAddress1'];
							$this->Paypal->billingAddress2 = $this->request->data['Customer']['bAddress2'];
							$this->Paypal->billingCity = $l['Locality']['locality'];
							$this->Paypal->billingState = $l['Locality']['state'];
							$this->Paypal->billingCountryCode = 200; // default for Australia
							$this->Paypal->billingZip = $l['Locality']['postCode'];
							$this->Paypal->creditCardType = $this->request->data['Customer']['cardtype'];
							$this->Paypal->creditCardNumber = $this->request->data['Customer']['cardnumber'];
							$this->Paypal->creditCardExpires = $this->request->data['Customer']['cardexp'];
							$this->Paypal->creditCardCvv = $this->request->data['Customer']['cardccv'];
							$this->Paypal->doDirectPayment();
						} 

						$this->redirect(array('controller' => 'orders', 'action' => 'view', $oID));

					} else {
						$this->Session->setFlash(__('Your order was not created, however your information has been sent to an administrator for continued processing. We will contact you shortly.'), 'Flash/error', array(), 'cart');
						$this->sendMail(
							Configure::read('MySite.send_email'),
							'Error when saving order to ProStore',
							'order-errorprocessing',
							array_merge(array('Customer' => $this->request->data['Customer']), $data)
						);
						$this->Session->delete('Cart');
					}

				} else {

					$this->Session->setFlash(__('There was a server error. Please try again.'), 'Flash/warning', array(), 'cart');
						
				}

			
			} else {

				$this->Session->setFlash(__('Please enter your details as noted below.'), 'Flash/error', array(), 'cart');

			}
			
			$this->set('errors', $this->Customer->validationErrors);
		}

		if ($this->Session->read('Customer')) {
			$this->request->data = $this->ProStore->getCustomer($this->Session->read('Customer'));
		}
				
		$this->set(compact('items'));

	}

	public function track_order() {
		$this->set('title_for_layout', __('Track your order'));
		if ($this->request->is('post')) {
			$order = $this->ProStore->getOrder(array('oID' => $this->request->data['Customer']['orderID'], 'cEmail' => $this->request->data['Customer']['cEmail']));
			if ($order != 'false') {

				$this->set(compact('order'));
				$this->render('order_view');	
				/*
				$this->sendMail(
					$this->request->data['Customer']['cEmail'],
					'New Order #' . $order['Order']['oID'],
					'order-new',
					$order
				);
				$this->theme = 'LG1';
				$this->set('data', $order);
				$this->render('/Emails/html/order-new', '/Emails/html/default');
				*/
			} else {
				$this->Session->setFlash(__('Incorrect Order Details'), 'Flash/error', array(), 'order');
				$this->render('login_small');
			}
		} else {
			$this->render('login_small');
		}
	}

	public function generatePassword($uid = false, $length = 9, $strength = 8) {
		
		$vowels = 'aeiou';
		$consonants = 'bcsfghjklmnpqrstvwxyz';
		if ($strength & 1) {
			$consonants .= 'BCDFGHJKLMNPQRSTVWXYZ';
		}
		if ($strength & 2) {
			$vowels .= "AEIOU";
		}
		if ($strength & 4) {
			$consonants .= '1234567890';
		}
		if ($strength & 8) {
			$consonants .= '@#$%!*_()^!?][{}|';
		}
		 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		if ($uid) {
			$this->Customer->User->id = $uid;
			$this->Customer->User->saveField('password', $password);
		}
		return $password;
		
	}
	
	public function hasOrders($cid = false) {
		/*if ($cid) {
			return true;
		}*/
		return false;
	}
	
	public function getID($user_id = false) {
		if ($user_id) {
			$r = $this->Customer->findByUserId($user_id);
			return $r['Customer']['id'];
		} 
		return 1;
	}
	
	public function getLocation() {
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$this->loadModel('Location');
			$result = $this->Location->getLocation($this->request->data['search']);
			if ($result) {
				return json_encode($result);
			}
		}
		return null;
	}
	
	private function _isItemInCart($pid){
		
		if ($this->Session->check('Cart')) {
			$_items = $this->Session->read('Cart.items');
			for ($i=0;$i<count($_items);$i++) {
				if ($pid==$_items[$i]['pid']) {
					return $i;
				}
			}
		}
		
		return -1;
	}
	
	private function _getItemsInCart(){
		
		$_cart = $this->Session->read('Cart.items');
		$_cartitems = '';
		
		if (is_array($_cart)) {
			for ($x=0;$x<count($_cart);$x++) {
				$_cartitems[$x] = $this->Cart->getCartProducts($_cart[$x]['pid']);
				$_cartitems[$x]['Product']['qty'] = $_cart[$x]['qty'];
			}
		}
		
		return $_cartitems;
		
	}

}
