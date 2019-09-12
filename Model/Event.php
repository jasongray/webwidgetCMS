<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 * @property User $User
 */
class Event extends AppModel {

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
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public function findFeatured($limit = 10) {
		
		$this->recursive = 0;
		$result = $this->find('all', array(
			'conditions' => array('Event.published' => 1, 'Event.featured' => 1), 
			'limit' => $limit
			)
		);
		return $result;
	}	

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val[$this->alias]['datetime']) && !empty($val[$this->alias]['datetime'])) {
				$results[$key][$this->alias]['datetime'] = strtotime($val[$this->alias]['datetime']);
			}
			if (isset($val[$this->alias]['enddatetime']) && !empty($val[$this->alias]['enddatetime'])) {
				$results[$key][$this->alias]['enddatetime'] = strtotime($val[$this->alias]['enddatetime']);
			}
		}
		return $results;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['datetime'])){
			if (empty($this->data[$this->alias]['datetime'])) {
				$this->data[$this->alias]['datetime'] = date('Y-m-d H:i:s');
			} else {
				$this->data[$this->alias]['datetime'] = date('Y-m-d H:i:s', strtotime($this->data[$this->alias]['datetime']));
			}
			if (empty($this->data[$this->alias]['enddatetime'])) {
				$this->data[$this->alias]['enddatetime'] = date('Y-m-d H:i:s');
			} else {
				$this->data[$this->alias]['enddatetime'] = date('Y-m-d H:i:s', strtotime($this->data[$this->alias]['enddatetime']));
			}
		}
		return true;
	}

	public function calendarEvents($conditions = array()) {
		$out = array();
		$this->recursive = -1;
		$this->virtualFields = array(
			'eventDate' => 'DATE_FORMAT(Event.datetime, "%Y/%m/%d")',
			'eventTitle' => 'GROUP_CONCAT(CONCAT(TIME_FORMAT(Event.datetime, "%h:%i%p"), " : ", Event.title) ORDER BY Event.datetime ASC SEPARATOR "\n")',
		);
		$data = $this->find('all', array(
				'fields' => array(
					'eventDate',
					'eventTitle',
				),
				'conditions' => $conditions,
				'order' => 'Event.datetime ASC',
				'group' => 'DATE_FORMAT(datetime, "%Y%m%d")',
			)
		);
		if ($data) {
			foreach ($data as $d) {
				$out[] = $d['Event'];
			}
		}
		return $out;
	}
	
}