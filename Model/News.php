<?php
App::uses('AppModel', 'Model');
/**
 * News Model
 *
 * @property User $User
 */
class News extends AppModel {

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
	
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'news_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public function findLatestNews() {
		
		$this->recursive = 0;
		$this->virtualFields = array(
			'commentcnt' => 'SELECT COUNT(Comment.id) FROM comments AS Comment WHERE Comment.news_id = News.id AND Comment.status = 1'
		);
		$result = $this->find('all', array(
			'conditions' => array('News.published' => 1), 
			'order' => array('News.created' => 'DESC'),
			'joins' => array(
				array(
					'table' => 'comments',
					'alias' => 'Comment',
					'type' => 'LEFT',
					'conditions'=> array('News.id = Comment.news_id', 'Comment.status' => 1)
					)
				),
			'group' => array('News.id'),
			'limit' => 9
			)
		);
		return $result;
	}	
	
	
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val[$this->alias]['start_publish']) && !empty($val[$this->alias]['start_publish'])) {
				$results[$key][$this->alias]['start_publish'] = date('d-m-Y', strtotime($val[$this->alias]['start_publish']));
			}
			if (isset($val[$this->alias]['end_publish']) && !empty($val[$this->alias]['end_publish'])) {
				$results[$key][$this->alias]['end_publish'] = date('d-m-Y', strtotime($val[$this->alias]['end_publish']));
			}
		}
		return $results;
	}
	
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['start_publish'])){
			if (empty($this->data[$this->alias]['start_publish'])) {
				$this->data[$this->alias]['start_publish'] = date('Y-m-d H:i:s');
			} else {
				$this->data[$this->alias]['start_publish'] = date('Y-m-d H:i:s', strtotime($this->data[$this->alias]['start_publish']));
			}
		}
		if (isset($this->data[$this->alias]['end_publish'])){
			if (!empty($this->data[$this->alias]['end_publish'])) {
				$this->data[$this->alias]['end_publish'] = date('Y-m-d H:i:s', strtotime($this->data[$this->alias]['end_publish']));
			}
		}
		if (isset($this->data[$this->alias]['tags']) && !empty($this->data[$this->alias]['tags'])){
			$tags = explode(',', $this->data[$this->alias]['tags']);
			App::uses('Tag', 'Model');
			$this->Tag = new Tag;
			foreach ($tags as $d) {
				$data['tag'] = $d;
				if (!$_tag = $this->Tag->find('first', array('conditions' => array('tag' => $data['tag'])))) {
					$this->Tag->create();
					$this->Tag->save($data);
				} else {
					if (!isset($this->data[$this->alias]['id'])) {
						$_tag['Tag']['count'] = $_tag['Tag']['count'] + 1;
						$this->Tag->save($_tag);
					}
				}
			}
		}
		return true;
	}
	
}