<?php
/**
 *	Products Constroller
 *
 *	Displays products in the products table
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

App::uses('AppController', 'Controller');

/**
 * Product controller
 *
 * @package       WebCart
 */
class ProductsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('index', 'home', 'search', 'promotions', 'view', 'postsearch'));
	}

/**
 * Displays home page
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function home() {

		$this->loadModel('Brand');
		$this->set('brands', $this->Brand->getBrands());

	}

/**
 * Displays paginated index of products
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function index() {
/*
		if (isset($this->params['brand']) && !isset($this->params['collection']) && (!isset($this->params['group']) && !isset($this->params['subgroup']) && !isset($this->params['category']) && !isset($this->params['subcategory']))) {
			$this->redirect(array('controller' => 'brands', 'action' => 'index', 'brand' => $this->params['brand']));
		}
		if (isset($this->params['brand']) && isset($this->params['collection']) && (!isset($this->params['group']) && !isset($this->params['subgroup']) && !isset($this->params['category']) && !isset($this->params['subcategory']))) {
			$this->redirect(array('controller' => 'brands', 'action' => 'collection', 'brand' => $this->params['brand'], 'collection' => $this->params['collection']));
		}
*/
		$this->paginateConditions();
		$this->set('products', $this->paginate('Product'));
		$this->getCategories();
		$this->getBrands();

	}

/**
 * Displays paginated index of products based on search results
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function search() {

		$this->paginateConditions();
		$this->set('products', $this->paginate('Product'));
		$this->getCategories();
		$this->getBrands();

		$this->render('index');

	}

/**
 * Displays paginated index of products based on promo result
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function promotions($promourl) {

		$this->paginateConditions();
		$this->set('products', $this->paginate('Product'));
		$this->getCategories();

		$promo = array();
		$promo = $this->Product->getPromo($promourl);
		if (!empty($promo)) {
			$this->set('title_for_layout', $promo['Product']['promoTitle']);
		}
		$this->set(compact('promo'));
		
		$this->getBrands();

		$this->render('index');

	}

/**
 * Displays specific product identified by the product id and option id
 *
 * @return array $product Array of a single product
 *
 */
	public function view($id = false) {
		if ($id) {
			$this->paginate = array();
			$product = $this->Product->getProductID($id);
			if ($product) {
				if (!empty($product['Product']['prdCollection'])) {
					$this->set('collections', $this->Product->getProductCollections($product['Product']['prdCollection']));
				}
				$this->set(compact('product'));
				$this->set('title_for_layout', $this->prdPageTitle($product));
				$this->set('trail', $this->referer());
			} else {
				$this->setFlash(__('Product no longer found.'));
				$this->redirect('/');
			}
		} else {
			$this->setFlash(__('Product no longer found.'));
			$this->redirect('/');
		}

	}

/**
 * Post Search method to redirect to index with post as query string
 *
 *
 */
	public function postsearch() {
		if ($this->request->is('post') || $this->request->is('put')) {
			if (isset($this->request->data['Product']['q'])) {
				$this->redirect(array('controller' => 'products', 'action' => 'search', 'keyword' => $this->request->data['Product']['q']));
			} else {
				$this->redirect($this->referer());
			}
		} else {
			$this->redirect($this->referer());
		}
	}


	private function prdPageTitle($p = array()) {
		if (!empty($p)) {
			return Inflector::humanize(ucwords(strtolower($p['Product']['prdCollection'] . ' ' . $p['Product']['prdTitle'] . ' by ' . $p['Product']['prdLineName'] . ' - ' . $p['Product']['prdLineTag'])));
		}
		return '';
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Product->recursive = 0;
		$this->set('products', $this->paginate());
	}

}
