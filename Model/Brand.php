<?php
/**
 *  Brand Model.
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
 * Brand model for WebCart.
 *
 * @package       WebCart
 */
class Brand extends AppModel {

	public $useTable = 'products';

	public function isBrand($brand = false) {
		if($brand) {
			$return = $this->find('first', array(
					'conditions' => array(
						'OR' => array(
							'Brand.prdLineName IS NOT NULL',
							'Brand.prdLineName <> \'\'',
						),
						'OR' => array(
							'Brand.prdCollection IS NOT NULL',
							'Brand.prdCollection <> \'\'',
						),
						'AND' => array(
							'Brand.prdStatusId' => 3,
						),
						'Brand.prdLineName' => array(Inflector::slug($brand, " ")),
					),	
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}

	public function isCollection($collection = false) {
		if($collection) {
			$return = $this->find('first', array(
					'conditions' => array(
						'OR' => array(
							'Brand.prdLineName IS NOT NULL',
							'Brand.prdLineName <> \'\'',
						),
						'OR' => array(
							'Brand.prdCollection IS NOT NULL',
							'Brand.prdCollection <> \'\'',
						),
						'AND' => array(
							'Brand.prdStatusId' => 3,
						),
						'Brand.prdCollection' => array(Inflector::slug($collection, " ")),
					),	
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}

	public function getBrands($moreConditions = array()) {
		
		$this->virtualFields = array('collections' => 'GROUP_CONCAT(DISTINCT prdCollection ORDER BY prdCollection)');
		$findoptions = array(
			'fields' => array(
				'prdLineName AS name',
				'prdLineImage AS image',
				'collections'
			),
			'conditions' => array(
				'OR' => array(
					'prdLineName IS NOT NULL',
					'prdLineName <> \'\'',
				),
				'OR' => array(
					'prdCollection IS NOT NULL',
					'prdCollection <> \'\'',
				),
				'AND' => array(
					'Brand.prdStatusId' => 3,
				),
				$moreConditions,
			),
			'group' => array(
				'prdLineName'
			),
			'order' => array(
				'prdLineName ASC'
			),
		);
		return $this->find('all', $findoptions);

	}

	public function getBrand($brand = false, $collection = false) {
		if($brand) {
			$conditions = array('prdLineName' => array(Inflector::slug($brand, " ")), 'prdStatusId' => 3);
			if ($collection) {
				$conditions = array_merge(array('prdCollection' => array(Inflector::slug($collection, " "))), $conditions);
			}
			$return = $this->find('first', array(
					'fields' => array(
						'prdLineName',
						'prdLineImage',
						'prdLineTag',
						'prdLineKeywords',
						'prdLineDescription',
						'prdLineBlurb',
						'prdLineWarrantyLink',
					),
					'conditions' => $conditions,
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}

	public function getCollection($brand = false, $collection = false) {
		if($brand && $collection) {
			$conditions = array('prdLineName' => array(Inflector::slug($brand, " ")), 'prdStatusId' => 3, 'prdCollection' => array(Inflector::slug($collection, " ")));
			$return = $this->find('first', array(
					'fields' => array(
						'prdLineName',
						'prdLineImage',
						'prdLineTag',
						'prdLineKeywords',
						'prdLineDescription',
						'prdLineBlurb',
						'prdLineWarrantyLink',
						'prdCollection',
					),
					'conditions' => $conditions,
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}
	
	public function getBrandsbyProduct($moreConditions = array()){
		
		$this->virtualFields = array(
			'brandorder' => 'SELECT COUNT(DISTINCT Brand.prd_OptionID)'
		);
		$findoptions = array(
			'fields' => array(
				'Brand.prdLineName AS name',
				'Brand.prdLineImage AS image',
			),
			'conditions' => array(
				'OR' => array(
					'Brand.prdLineName IS NOT NULL',
					'Brand.prdLineName <> \'\'',
				),
				'OR' => array(
					'Brand.prdCollection IS NOT NULL',
					'Brand.prdCollection <> \'\'',
				),
				'AND' => array(
					'Brand.prdLineImage IS NOT NULL',
					'Brand.prdLineImage <> \'\'',
					'Brand.prdStatusId' => 3,
				),
				$moreConditions,
			),
			'group' => array(
				'Brand.prdLineName'
			),
			'order' => array(
				'brandorder DESC'
			),
			'limit' => 18
		);
		return $this->find('all', $findoptions);
		
	}
	
}
