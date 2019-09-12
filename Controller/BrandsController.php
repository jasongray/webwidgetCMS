<?php
/**
 *	Brands Constroller
 *
 *	Displays products in the products table by brand
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
 * Brand controller
 *
 * @package       WebCart
 */
class BrandsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('index', 'collection', 'category'));
	}	

/**
 * Displays paginated index of products by brand
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function index() {

		$this->paginateConditions();

		$this->loadModel('Product');
		$this->set('products', $this->paginate('Product'));
		$this->getCategories();
		$this->set('brands', $this->Brand->getBrands());

		$brand = array();
		$brand = $this->Brand->getBrand($this->params['brand']);
		if (!empty($brand)) {
			if (isset($this->params['collection']) && !empty($this->params['collection'])) { 
				$this->set('title_for_layout', Inflector::humanize($brand['Brand']['prdLineName']) . ' ' . Inflector::humanize($this->params['collection']));
			} else {
				$this->set('title_for_layout', $brand['Brand']['prdLineName']);
			}
		}
		$this->set(compact('brand'));

	}

/**
 * Displays paginated index of products by brand/collection
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function collection() {

		$this->paginateConditions();

		$this->loadModel('Product');
		$this->set('products', $this->paginate('Product'));
		$this->getCategories();
		$this->set('brands', $this->Brand->getBrands());
		$brand = array();
		$brand = $this->Brand->getBrand($this->params['brand']);
		if (!empty($brand)) {
			$this->set('title_for_layout', Inflector::humanize($brand['Brand']['prdLineName']) . ' ' . Inflector::humanize($this->params['collection']));
		}
		$this->set(compact('brand'));
		$this->render('index');

	}

/**
 * Displays paginated index of products by brand category
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function category() {

		$this->paginateConditions();

		$this->loadModel('Product');
		$this->set('products', $this->paginate('Product'));
		$this->getCategories();
		$this->set('brands', $this->Brand->getBrands());
		$brand = array();
		$brand = $this->Brand->getBrand($this->params['brand']);
		if (!empty($brand)) {
			$this->set('title_for_layout', Inflector::humanize($brand['Brand']['prdLineName']) . ' ' . Inflector::humanize($this->params['category']) . ' ' . Inflector::humanize($this->params['subcategory']));
		}
		$this->set(compact('brand'));
		$this->render('index');
	}

/**
 * Displays paginated index of products by brand collection category
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function collectioncategory() {

		$this->paginateConditions();

		$this->loadModel('Product');
		$this->set('products', $this->paginate('Product'));
		$this->getCategories();
		$this->set('brands', $this->Brand->getBrands());
		$brand = array();
		$brand = $this->Brand->getBrand($this->params['brand']);
		if (!empty($brand)) {
			$this->set('title_for_layout', Inflector::humanize($brand['Brand']['prdLineName']) . ' ' . Inflector::humanize($this->params['category']) . ' ' . Inflector::humanize($this->params['subcategory']));
		}
		$this->set(compact('brand'));
		$this->render('index');
	}

}
