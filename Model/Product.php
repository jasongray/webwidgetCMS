<?php
/**
 * Product Model.
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
 * Product model for WebCart.
 *
 * @package       WebCart
 */
class Product extends AppModel {

	public $displayField = 'title';


	public function getProductID($prdID = false) {
		if($prdID) {
			$this->virtualFields = array(
				'options' => 'GROUP_CONCAT(prd_OptionID)',
				'colours' => 'GROUP_CONCAT(prdColour)',
				'sizes' => 'GROUP_CONCAT(prdSize)',
				'retail' => 'GROUP_CONCAT(prdRRP)',
				'sell' => 'GROUP_CONCAT(prdSellPrice)',
				'images' => 'GROUP_CONCAT(prdPrimaryImage)'
			);
			return $this->find('first', array(
					'conditions' => array(
						'prdStatusID = 3',
						'prdID' => $prdID
					)
				)
			);

		}
	}

	public function getPromo($promourl = false) {
		if($promourl) {
			$this->virtualFields = array('collections' => 'GROUP_CONCAT(DISTINCT prdCollection ORDER BY prdCollection)');
			return $this->find('first', array(
					'fields' => array(
						'promoTitle', 
						'promoDescription',
						'promoFinishDate',
						'promoUrl',
						'prdLineName',
						'prdLineImage',
						'collections',
					),
					'conditions' => array(
						'promoUrl' => array(Inflector::slug($promourl, " "))
					),
				)
			);
		}
	}

	public function getSizes($moreConditions = array())  {
		$this->virtualFields = array('sizes' => 'GROUP_CONCAT(DISTINCT prdSize ORDER BY prdSize)');
		$result = $this->find('first', array(
				'fields' => array(
					'sizes',
				),
				'conditions' => array(
					'AND' => array(
						'prdSize IS NOT NULL',
						'prdSize <> 0',
						'prdSize <> ""',
					),
					$moreConditions
				),
			)
		);
		if ($result) {
			return explode(',', $result['Product']['sizes']);
		}
		return array();
	}

	public function getProductCollections($collection = false) {
		$this->virtualFields = array(
				'options' => 'GROUP_CONCAT(prd_OptionID)',
				'colours' => 'GROUP_CONCAT(prdColour)',
				'sizes' => 'GROUP_CONCAT(prdSize)',
				'retail' => 'GROUP_CONCAT(prdRRP)',
				'sell' => 'GROUP_CONCAT(prdSellPrice)',
				'images' => 'GROUP_CONCAT(prdPrimaryImage)'
			);
		return $this->find('all', array(
					'conditions' => array(
						'prdStatusID = 3',
						'prdCollection' => $collection
					),
					'group' => 'prdID',
				)
			);
	}
	
}
