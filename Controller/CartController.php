<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Cart Controller
 *
 * @property Cart $Cart
 */
class CartController extends AppController {

	var $components = array('AusPost');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('calcpostage', 'addInsurance', 'index', 'add' ,'update', 'remove'));
	}

	public function calcpostage() {
		$this->autoRender = false;

		if ($this->request->is('post') || $this->request->is('put')) {

			if (isset($this->request->data['to_postcode']) && !empty($this->request->data['to_postcode'])) {
				$this->Session->write('Cart.to_postcode', $this->request->data['to_postcode']);
			} else {
				$this->request->data['to_postcode'] = $this->Session->read('Cart.to_postcode');
			}

			$this->AusPost->set('from_postcode', Configure::read('MySite.AusPost.from_postcode'));
			$this->AusPost->set('to_postcode', $this->request->data['to_postcode']);

			$mycart = $this->Session->read('Cart.items');

			$_cartitems = array();

			$width = 0;
			$height = 0;
			$length = 0;
			$weight = 0;

			if (!empty($mycart)) {
				$this->loadModel('Cart');
				if (is_array($mycart)) {
					for ($x=0;$x<count($mycart);$x++) {
						$_cartitems[$x] = $this->Cart->getCartProducts($mycart[$x]['pid']);
						$_cartitems[$x]['Product']['qty'] = $mycart[$x]['qty'];
						$width = $width + $_cartitems[$x]['Product']['qty'] * $_cartitems[$x]['Product']['prdDepth'];
						$height = $height + $_cartitems[$x]['Product']['qty'] * $_cartitems[$x]['Product']['prdHeight'];
						$length = $length + $_cartitems[$x]['Product']['qty'] * $_cartitems[$x]['Product']['prdWidth'];
						$weight = $weight + $_cartitems[$x]['Product']['qty'] * $_cartitems[$x]['Product']['prdWeight'];
					}
				}
			}

			$this->AusPost->set('length', $length);
			$this->AusPost->set('width', $width);
			$this->AusPost->set('height', $height);
			$this->AusPost->set('weight', $weight/1000);

			$val = $this->AusPost->postage();
			$this->Session->write('Cart.shipping', $val);
			$this->Cart->saveCart($this->Session->id(), $this->Session->read('Cart'));
			echo $val;

		}
	}

	public function addInsurance() {
		if ($this->request->is('post') && $this->request->is('ajax')) {
			$this->Session->write('Cart.insurance', $this->request->data['insurance']);
			$this->Cart->saveCart($this->Session->id(), $this->Session->read('Cart'));
		}
		$this->index();
		$this->render('index');
	}
/**
 * index method
 *
 * @return void
 */		
	public function index() {		
		$cartitems = $this->Session->read('Cart.items');
		$items = $this->_getItemsInCart();
		if (empty($items)) {
			$this->Session->setFlash(__('There are no items in your cart.'), 'Flash/warning');
			//$this->redirect(array('controller' => 'products', 'action' => 'custom'));
		}
		$this->set(compact('cartitems', 'items'));
	}

/**
 * add method
 *
 * @return void
 */	
	public function add() {
		$this->autoRender = false;
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$id = $this->request->data['Product']['pid'] = $this->request->data['pid'];
			$qty = (!empty($this->request->data['Product']['qty']))? $this->request->data['Product']['qty']: 1;
			$qty = (!empty($this->request->data['qty']))? $this->request->data['qty']: $qty;
			if ($id) {
				$_i = $this->_isItemInCart($id);
				if ($_i > -1) {
					$__qty = 0;
					$__p = $this->Session->read('Cart.items');
					$__qty = $__p[$_i]['qty'] + $qty;
					$this->Session->write('Cart.items.'.$_i.'.qty', $__qty);
				} else {
					$idx = $this->Session->check('Cart.items')? count($this->Session->read('Cart.items')): 0;
					$this->Session->write('Cart.items.'.$idx.'.pid', $id);
					$this->Session->write('Cart.items.'.$idx.'.qty', $qty);
				}
				$_p = $this->Cart->getProductTitle($id);
				$this->Cart->saveCart($this->Session->id(), $this->Session->read('Cart'));
				if ($this->request->is('ajax')) {
					$_p = array_merge_recursive($_p, array('Product' => array('qty' => $qty)));
					echo json_encode($_p);
				} else {
					$this->redirect(array('controller' => 'cart', 'action' => 'index'));
				}
				
			}
			
		}
		
	}
	
/**
 * update method
 *
 * @return void
 */	
	public function update() {
		$this->autoRender = false;
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$id = $this->request->data['Product']['pid'] = $this->request->data['pid'];
			$qty = (!empty($this->request->data['Product']['qty']))? $this->request->data['Product']['qty']: 1;
			$qty = (!empty($this->request->data['qty']))? $this->request->data['qty']: $qty;
			if ($id) {
				$_i = $this->_isItemInCart($id);
				if ($qty == 0 && $_i == -1) {
					$this->Session->delete('Cart.items.'.$_i);
				} else {
					if ($_i > -1) {
						$this->Session->write('Cart.items.'.$_i.'.qty', $qty);
					} else {
						$idx = $this->Session->check('Cart')? count($this->Session->read('Cart')): 0;
						$this->Session->write('Cart.items.'.$idx.'.pid', $id);
						$this->Session->write('Cart.items.'.$idx.'.qty', $qty);
						if (isset($this->request->data['position'])) {
							$this->Session->write('Cart.items.'.$idx.'.position', $this->request->data['position']);
						}
					}
				}
				$this->Cart->saveCart($this->Session->id(), $this->Session->read('Cart'));
				if (isset($this->request->params['named']['layout']) && $this->request->params['named']['layout'] == 'ajax') {
					echo true;
				} else {
					$this->redirect(array('controller' => 'cart', 'action' => 'index'));
				}
				
			}
			
		}
		
	}

/**
 * remove method
 *
 * @return void
 */	
	public function remove($id = null) {
		$this->autoRender = false;
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$id = $this->request->data['Product']['pid'] = $this->request->data['pid'];
		}
		if ($id) {
			$_i = $this->_isItemInCart($id);
			if ($_i > -1) {
				$this->Session->delete('Cart.items.'.$_i);
				$cartitems = $this->Session->read('Cart.items');
				$cartitems = array_merge(array(), $cartitems);
				if (empty($cartitems)) {
					$this->Session->delete('Cart');
				} else {
					$this->Session->delete('Cart.items');
					$this->Session->write('Cart.items', $cartitems);
				}
			}
			$this->Cart->saveCart($this->Session->id(), $this->Session->read('Cart'));
			if (isset($this->request->params['named']['layout']) && $this->request->params['named']['layout'] == 'ajax') {
				echo 'Items removed.';
			} else {
				$this->redirect(array('controller' => 'cart', 'action' => 'index'));
			}	
		}
		
	}

	public function emptycart(){
		$this->Session->delete('Cart');
		$this->Cart->saveCart($this->Session->id(), array());
	}

	private function _isItemInCart($pid){
		
		if ($this->Session->check('Cart.items')) {
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


	public function admin_index() {
		$this->Cart->recursive = 0;
		$this->paginate = array('conditions' => array('cart_value IS NOT NULL'), 'order' => 'created DESC');
		$carts = $this->paginate();
		if ($carts) {
			for ($i=0;$i<count($carts);$i++) {
				$_cart = json_decode($carts[$i]['Cart']['cart_value'], true);
				for ($x=0;$x<count($_cart['items']);$x++) {
					if ($products = $this->Cart->getCartProducts($_cart['items'][$x]['pid'])) {
						$carts[$i]['CartItems'][$x] = $products;
						$carts[$i]['CartItems'][$x]['Product']['qty'] = $_cart['items'][$x]['qty'];
					}
				}
			}
		}
		$this->set(compact('carts'));
	}

	public function admin_delete($id = false) {
		$this->autoRender = false;
		$this->Cart->id = $id;
		if (!$this->Cart->exists()) {
			throw new NotFoundException(__('Invalid Cart'));
		}
		if ($this->Cart->delete()) {
			$this->Session->setFlash(__('Cart deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Cart was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
	

}