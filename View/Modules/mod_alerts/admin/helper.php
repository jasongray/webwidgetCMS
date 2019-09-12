<?php 

/**
 *	mod_alerts class
 *
 *
 */

class mod_alerts  {

	public function getAlerts($m) {
		$conditions = array(); $limit = array();
		if (isset($m['limit'])) {
			$limit = array('limit' => $m['limit']);
		}
		App::uses('Alert', 'Model');
		$this->Alert = new Alert;
		return $this->Alert->find('all', array_merge(array(
			'fields' => array(
				'id',
				'title',
				'content',
				'hexcode',
			),
			'conditions' => array_merge(array('Alert.publish' => 1, '(NOW() BETWEEN Alert.start_date AND IF(Alert.end_date IS NULL, NOW(), Alert.end_date))'), $conditions),
			'order' => 'Alert.start_date ASC, Alert.created DESC',
			), 
			$limit)
		);
	}

}
?>