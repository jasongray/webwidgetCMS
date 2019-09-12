<?php
/**
 *  Location Model.
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
 * Location model for WebCart.
 *
 * @package       WebCart
 */
class Location extends AppModel {

	public $useTable = 'locality';

	public function getLocation($str) {
		$array = array();
		$this->virtualFields = array('locality' => 'CONCAT(locality, ", ", state, " ", postCode)');
		$result = $this->find('all', array(
			'fields' => array(
				'suburbID', 'locality'
			),
			'conditions' => array(
				'OR' => array(
					'locality LIKE ?' => $str.'%',
					'postCode LIKE ?' => $str.'%',
				)
			),
		));
		if ($result) {
			for($i=0; $i<count($result); $i++) {
				$l = $result[$i];
				$array[] = array('id' => $l['Location']['suburbID'], 'locality' => $l['Location']['locality']);
			}
		}
		return $array;
	}
	
}
