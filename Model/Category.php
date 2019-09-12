<?php
/**
 *  Category Model.
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
 * Category model for WebCart.
 *
 * @package       WebCart
 */
class Category extends AppModel {

	public $actsAs = array('Tree');

	public function isCategory($category = false) {
		if($category) {
			$conditions = array('Category.title' => Inflector::slug($category, " "));
			$return = $this->find('first', array(
					'conditions' => array_merge(array('Category.parent_id IS NULL'), $conditions),
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}

	public function isSubCategory($category = false) {
		if($category) {
			$conditions = array('Category.title LIKE' => "%".Inflector::slug($category, " "));
			$return = $this->find('first', array(
					'conditions' => array_merge(array('Category.parent_id IS NOT NULL'), $conditions),
					
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}

	public function getCategories($moreConditions = array()) {
		
		$array = array();
		App::uses('Product', 'Model');
		$this->Product = new Product;
		$results = $this->Product->find('all', array('fields' => 'prdCategoryArray', 'conditions' => $moreConditions));
		if ($results) {
			for ($i=0; $i<count($results); $i++ ) {
				$array[] = $results[$i]['Product']['prdCategoryArray'];
			}
			
			return $this->saveCategoryArray($array);

		}

		return $array;

	}

/**
*	Converts a delimited string of categories into a parent/child array.
*
*	@param string $str string of category names or id.
*	@param string $dlm1 Separator of Category lists.
*	@param string $dlm1 Separator of sub categories.
*	@return array Array of Main Category with children subcategories.
*/
	public function saveCategoryArray ($str = false, $dlm1 = ',', $dlm2 = '>') {

		$this->dlm1 = $dlm1;
		$this->dlm2 = $dlm2;

		if (is_array($str)) {
			$cats = array();
			foreach ($str as $s) {
				$cats = array_merge_recursive($cats, $this->strToArray($s));
			}
		} else {
			$cats = $this->strToArray($str);
		}
		
		return $cats;
		
	}

/**
*	Private function to explode string into an assoicative array.
*
*	@param string $str string of category names or id.
*	@return array Array.
*/
	private function strToArray ($str = false) {

		$categories = array();
		$array = array();

		if ($str) {
			$_cats = array_map(
				function ($substr) {
					return explode($this->dlm2, $substr);
				}, 
				explode($this->dlm1, $str)
			);

			foreach ($_cats as $c) {	
				$array = array();
				foreach (array_reverse($c) as $arr) {
					$array = array($arr => $array);
				}
				$categories = array_merge_recursive($array, $categories);
			}
		
		}

		return $categories;

	}

	public function getCategory($category = false) {
		if($category) {
			$conditions = array('Category.title' => array(Inflector::slug($category, " ")));
			$return = $this->find('first', array(
					'fields' => array(
						'id',
						'title',
						'parent_id',
					),
					'conditions' => array_merge(array('Category.parent_id IS NULL'), $conditions),
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}

	public function getSubCategory($category, $subcategory = false) {
		if($subcategory) {
			$result = $this->getCategory($category);
			if ($result){
				$return = $this->find('first', array(
						'fields' => array(
							'title',
						),
						'conditions' => array(
							'MATCH(Category.title) AGAINST(? IN BOOLEAN MODE)' => '"' . Inflector::slug($subcategory, " ") . '"',
							'Category.parent_id' => $result['Category']['id'],
						),
					)
				);
				if (!empty($return)) {
					return $return;
				}
			}

		}
		return false;

	}

}
