<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Orders Controller
 *
 * @property Order $Order
 */
class OrdersController extends AppController {
	
	public $components = array('Paypal', 'ProStore');

	function beforeFilter() {
	    parent::beforeFilter();
		$this->Auth->allowedActions = array('view', 'complete_paypal'); 
		if (!$this->Session->read('Auth.User.id') && !$this->Session->read('Customer') && $this->request->params['action'] != 'login'  && $this->request->params['action'] != 'register' && $this->request->params['action'] != 'complete_payment' && $this->request->params['action'] != 'complete_paypal') {
			$this->Session->setFlash(__('You need to login to view your orders.'), 'Flash/admin_info');
			$this->redirect(array('controller' => 'customers', 'action' => 'login'));
		}
	}


/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		
		$c = $this->ProStore->getCustomer($this->Session->read('Customer'));

		$order = $this->ProStore->getOrder(array('oID' => $id, 'cEmail' => $c['Customer']['cEmail']));
		
		if ($this->request->is('post')) {
			
			if (isset($this->request->data['Order']['paymentmethod']) && $this->request->data['Order']['paymentmethod'] == 'paypal') {
				$this->Paypal->amount = number_format($order['Order']['grandtotal'], 2);
				$this->Paypal->returnUrl = Router::url(array('controller' => 'orders', 'action' => 'complete_paypal', $order['Order']['oID']), true);
				$this->Paypal->cancelUrl = Router::url(array('controller' => 'orders', 'action' => 'view', $order['Order']['oID']), true);
				$this->Paypal->itemName = 'Order #'.$order['Order']['oID'];
				$this->Paypal->quantity = 1;
				$this->Paypal->customerFirstName = $order['Customer']['firstname'];
				$this->Paypal->customerLastName = $order['Customer']['surname'];
				$this->Paypal->customerEmail = $order['Customer']['email'];
				$this->Paypal->billingAddress1 = $order['Order']['bill_address1'];
				$this->Paypal->billingAddress2 = $order['Order']['bill_address2'];
				$this->Paypal->billingCity = $order['Order']['bill_suburb'];
				$this->Paypal->billingState = $order['Order']['bill_state'];
				$this->Paypal->billingCountryCode = $order['Order']['bill_country_id'];
				$this->Paypal->billingZip = $order['Order']['bill_postcode'];
				$this->Paypal->expressCheckout();
			} 
			
		}
		
		if (!$order) {
			$this->Session->setFlash(__('You need to login to view your orders.'), 'Flash/admin_info');
			$this->redirect(array('controller' => 'customers', 'action' => 'login'));
		}
		$this->set(compact('order'));
		
	}
	
/**
 * complete_payment method
 *
 * @param string $id
 * @return void
 */
	public function complete_paypal($id = null, $token = false, $payerId = false) {
		$this->autoRender = false;

		$c = $this->ProStore->getCustomer($this->Session->read('Customer'));

		$order = $this->ProStore->getOrder(array('oID' => $id, 'cEmail' => $c['Customer']['cEmail']));

		if (!$token && !$payerId) {
			// for POST variables = $token = $this->request->data['Paypal']['token'];
			// for POST variables = $payerId = $this->request->data['Paypal']['PayerID'];
			$token = $this->params['url']['token'];
			$payerId = $this->params['url']['PayerID'];
		}

		$this->Paypal->amount = $order['Order']['oOrderTotal'];
		$this->Paypal->token = $token;
		$this->Paypal->payerId = $payerId;
		$response = $this->Paypal->doExpressCheckoutPayment();

		if ($response) {

			foreach ($response as $k => $v) {
				$_data[strtolower($k)] = $v;
			}
				
			switch ($response['ACK']) {
				case 'Success':
					$data['Payment']['oID'] = $order['Order']['oID'];
					$data['Payment']['cID'] = $order['Order']['cID'];
					$data['Payment']['tx'] = $response['TRANSACTIONID'];
					$data['Payment']['event_amount'] = $response['AMT'];
					$data['Payment']['type'] = 'PAYPAL';
					$this->Session->setFlash(__('Thank you for your payment!'), 'Flash/success', array(), 'cart');
				break;
				default:
					$data['Payment']['oID'] = $order['Order']['oID'];
					$data['Payment']['cID'] = $order['Order']['cID'];
					$data['Payment']['tx'] = 'Error ' . $response['TRANSACTIONID'];
					$data['Payment']['event_amount'] = 0;
					$data['Payment']['type'] = 'PAYPAL';
					$this->Session->setFlash(__('There was an error with your payment!'), 'Flash/error', array(), 'cart');
				break;
			}

			$this->ProStore->savePayment($data);
			$this->Session->delete('Cart');

			$this->loadModel('Payment');
			$this->Payment->save($data);
				
		} else {

			$this->Session->setFlash(__('For some reason your payment did not go through. Please try again later.'), 'Flash/error', array(), 'cart');

		}

		$this->redirect(array('controller' => 'orders', 'action' => 'view', $id));

	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Order->recursive = 0;
		$this->paginate = array(
			'limit' => 25,
			'order' => 'Order.created DESC'
		);
		$this->set('orders', $this->paginate());
	}

/**
 * admin_cancel method
 *
 * @param string $id
 * @return void
 */
	function admin_cancel($id = null) {
		$this->Session->setFlash(__('Operation cancelled'), 'Flash/admin_info');
		$this->redirect(array('action' => 'index'));
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Order->id = $id;
		if (!$this->Order->exists()) {
			throw new NotFoundException(__('Invalid order'));
		}
		$order = $this->Order->read(null, $id);
		if ($order) {
			$this->loadModel('Customer');
			$this->set('customer', $this->Customer->read(null, $order['Order']['customer_id']));
		}
		$orderStatuses = $this->Order->OrderStatus->find('list');
		$this->set(compact('order', 'orderStatuses'));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Order->create();
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash(__('The order has been saved'), 'Flash/admin_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The order could not be saved. Please, try again.'), 'Flash/admin_error');
			}
		}
		$customers = $this->Order->Customer->find('list');
		$orderStatuses = $this->Order->OrderStatus->find('list');
		$this->set(compact('customers', 'orderStatuses'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Order->id = $id;
		if (!$this->Order->exists()) {
			throw new NotFoundException(__('Invalid order'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash(__('The order has been saved'), 'Flash/admin_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The order could not be saved. Please, try again.'), 'Flash/admin_error');
			}
		} else {
			$this->request->data = $this->Order->read(null, $id);
		}
		$customers = $this->Order->Customer->find('list');
		$orderStatuses = $this->Order->OrderStatus->find('list');
		$this->set(compact('customers', 'orderStatuses'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Order->id = $id;
		if (!$this->Order->exists()) {
			throw new NotFoundException(__('Invalid order'));
		}
		if ($this->Order->delete()) {
			$this->Session->setFlash(__('Order deleted'), 'Flash/admin_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Order was not deleted'), 'Flash/admin_error');
		$this->redirect(array('action' => 'index'));
	}

		
}
