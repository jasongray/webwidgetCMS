<?php
/**
 *  Cart Model.
 *
 *	Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *
 *	Licensed under The MIT license
 *	Redistributions of this file must retain the above copyright notice
 *
 *	@copyright  	Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *	@package		WebCart
 *	@author			Jason Gray
 *	@version		1.8
 *	@license 		http://www.opensource.org/licenses/mit-license.php MIT License
 *
 */

App::uses('AppModel', 'Model');

/**
 * Cart model for WebCart.
 *
 * @package       WebCart
 */
class Cart extends AppModel {

	//public $useTable = false;
	public $primaryKey = 'session_id';

	public function getCartProducts($id) {
		
		App::uses('Product', 'Model');
		$this->Product = new Product;
		return $this->Product->find('first', array('conditions' => array('prd_OptionID' => $id)));

	}

	public function getProductTitle($id) {

		App::uses('Product', 'Model');
		$this->Product = new Product;
		$product = $this->Product->find('first', array('fields' => array('prdTitle', 'prdLineName', 'prdCollection', 'prdPrimaryImage', 'prdSellPrice'), 'conditions' => array('prd_OptionID' => $id)));
		if ($product) {
			return $product;
		}

	}

	public function saveCart($session_id = false, $cart = array()) {
		if ($session_id) {
			$data = array(
				'session_id' => $session_id,
				'cart_value' => json_encode($cart),
			);
			$this->save($data);
		}
	}

	public function removeCart($session_id = false) {
		if ($session_id) {
			$this->delete(array('session_id' => $session_id));
		}
	}
	
}
