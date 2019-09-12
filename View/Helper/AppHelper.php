<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {

	public function slug($text) {
		return Inflector::slug(strtolower($text), '-');
	}

	public function nice($text) {
		return Inflector::humanize(str_replace('-', ' ', strtolower($text)));
	}

	public function plural($text) {
		$text = $this->nice($text);
		return Inflector::pluralize($text);
	}

	public function categoryMenu() {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		return $this->Category->find('threaded', array('order' => 'Category.lft ASC'));
	}

	public function groupMenu() {
		App::uses('Group', 'Model');
		$this->Group = new Group;
		return $this->Group->find('threaded', array('order' => 'Group.lft ASC'));
	}

	public function cartInsurance($val) {
		$val = $val * Configure::read('MySite.insurance');
		$_vals = explode('.', $val);
		return $_vals[0].'.95';
	}

/**
 * Returns an array for url creation by removing the param keys specified in $remove
 *
 * url order   :group/:subgroup/:category/:subcategory/:brand/:collection
 *
 *
 * @param $params Array of named params
 * @param $remove Array of keys to be removed
 * @param $add Array of keys to add to the final array
 * @return array Array of data for url creation
 *
 */
	public function myurl($params = false, $remove = array(), $add = array()) {
		$url = array();
		if ($params) {

			if (isset($params['group']) && !in_array('group', $remove)) {
				$url['group'] = $this->slug($params['group']);
			}
			if (in_array('group', $remove)) {
				unset($url['group']);
			}
			if (array_key_exists('group', $add)) {
				$url['group'] = $this->slug($add['group']);
				unset($add['group']);
			}

			if (isset($params['subgroup']) && !in_array('subgroup', $remove)) {
				$url['subgroup'] = $this->slug($params['subgroup']);
			}
			if (in_array('subgroup', $remove)) {
				unset($url['subgroup']);
			}
			if (array_key_exists('subgroup', $add)) {
				$url['subgroup'] = $this->slug($add['subgroup']);
				unset($add['subgroup']);
			}

			if (isset($params['category']) && !in_array('category', $remove)) {
				$url['category'] = $this->slug($params['category']);
			}
			if (in_array('category', $remove)) {
				unset($url['category']);
			}
			if (array_key_exists('category', $add)) {
				$url['category'] = $this->slug($add['category']);
				unset($add['category']);
			}

			if (isset($params['subcategory']) && !in_array('subcategory', $remove)) {
				$url['subcategory'] = $this->slug($params['subcategory']);
			}
			if (in_array('subcategory', $remove)) {
				unset($url['subcategory']);
			}
			if (array_key_exists('subcategory', $add)) {
				$url['subcategory'] = $this->slug($add['subcategory']);
				unset($add['subcategory']);
			}

			if (isset($params['brand']) && !in_array('brand', $remove)) {
				$url['brand'] = $this->slug($params['brand']);
			}
			if (in_array('brand', $remove)) {
				unset($url['brand']);
			}
			if (array_key_exists('brand', $add)) {
				$url['brand'] = $this->slug($add['brand']);
				unset($add['brand']);
			}

			if (isset($params['collection']) && !in_array('collection', $remove)) {
				$url['collection'] = $this->slug($params['collection']);
			}
			if (in_array('collection', $remove)) {
				unset($url['collection']);
			}
			if (array_key_exists('collection', $add)) {
				$url['collection'] = $this->slug($add['collection']);
				unset($add['collection']);
			} 

			$url = $url+$add;

		}
		
		return $url;
		
	}

}
