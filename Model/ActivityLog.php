<?php
App::uses('AppModel', 'Model');
/**
 * ActivityLog Model
 *
 * @property User $User
 * @property Company $Company
 * @property Group $Group
 */
class ActivityLog extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	public function getRecent() {
		$this->recursive = -1;
		return $this->find('all', array('order' => array('ActivityLog.created' => 'DESC'), 'limit' => 5));
	}
	
	public function getMemberlogs($recent = false) {
		$this->recursive = -1;
		if ($recent) {
			return $this->find('all', array('conditions' => array("ActivityLog.description LIKE 'Member:%'", 'ActivityLog.viewed' => 0), 'order' => array('ActivityLog.created' => 'DESC'), 'limit' => 6));
		} else {
			return $this->find('all', array('conditions' => array("ActivityLog.description LIKE 'Member:%'", 'ActivityLog.viewed' => 0), 'order' => array('ActivityLog.created' => 'DESC'), 'limit' => 25));
		}
	}

}
