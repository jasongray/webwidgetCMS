<?php
/**
 *  Group Model.
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
 * Group model for WebCart.
 *
 * @package       WebCart
 */
class Group extends AppModel {

	public $actsAs = array('Tree');

	public function getGroups() {
		return $this->find('threaded');
	}

	public function isGroup($group = false) {
		if($group) {
			$conditions = array('Group.title' => array(Inflector::slug($group, " ")));
			$return = $this->find('first', array(
					'conditions' => array_merge(array('Group.parent_id IS NULL'), $conditions),
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}

	public function isSubGroup($subgroup = false) {
		if($subgroup) {
			$conditions = array('Group.title' => array(Inflector::slug($subgroup, " ")));
			$return = $this->find('first', array(
					'conditions' => array_merge(array('Group.parent_id IS NOT NULL'), $conditions),
					
				)
			);
			if (!empty($return)) {
				return $return;
			}
		}
		return false;
	}

}
