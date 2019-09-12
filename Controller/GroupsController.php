<?php
/**
 *	Groups Constroller
 *
 *	Displays products in the products table by group
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
 * Group controller
 *
 * @package       WebCart
 */
class GroupsController extends AppController {

/**
 * Displays paginated index of products by group
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function index() {

		$this->paginateConditions();

		$this->loadModel('Product');
		$this->set('products', $this->paginate('Product'));

		$this->getCategories();
		$this->getBrands();
		$this->getSizes();

		$group = array();
		$group = $this->Group->getGroup($this->params['group']);
		if (!empty($group)) {
			$this->set('title_for_layout', Inflector::humanize($group['Group']['title']));
		}
		$this->set(compact('group'));

	}

/**
 * Displays paginated index of products by brand/collection
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function subgroups() {

		$this->index();

		$group = array();
		$group = $this->Group->getSubGroup($this->params['subgroup']);
		if (!empty($group)) {
			$this->set('title_for_layout', Inflector::humanize($group['Group']['parent']) . ' ' . Inflector::humanize($group['Group']['title']));
		}
		$this->set(compact('group'));

		$this->render('index');

	}

/**
 * Displays paginated index of products by brand/collection
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function brand() {

		$this->index();

		$group = array();
		$group = $this->Group->getSubGroup($this->params['subgroup']);
		if (!empty($group)) {
			$this->set('title_for_layout', Inflector::humanize($group['Group']['parent']) . ' ' . Inflector::humanize($group['Group']['title']) . ' ' . Inflector::humanize($this->params['brand']));
		}
		$this->set(compact('group'));

		$this->render('index');

	}

/**
 * Displays paginated index of products by brand/collection
 *
 * @return array $products Array of products matching the conditions
 *
 */
	public function collection() {

		$this->index();

		$group = array();
		$group = $this->Group->getSubGroup($this->params['subgroup']);
		if (!empty($group)) {
			$this->set('title_for_layout', Inflector::humanize($group['Group']['parent']) . ' ' . Inflector::humanize($group['Group']['title']) . ' ' . Inflector::humanize($this->params['brand']) . ' ' . Inflector::humanize($this->params['collection']));
		}
		$this->set(compact('group'));

		$this->render('index');

	}

}
