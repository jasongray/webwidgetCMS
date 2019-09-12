<?php
App::uses('AppModel', 'Model');
/**
 * MenuItem Model
 *
 * @property Menu $Menu
 */
class MenuItem extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	
	public $actsAs = array('Tree');

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Menu' => array(
			'className' => 'Menu',
			'foreignKey' => 'menu_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	public function pageVars($request) {
		$_return = array();
		$_result = $this->find('first', array('conditions' => array('MenuItem.published' => 1, 'MenuItem.url' => '/'.$request->url)));
		if ($_result) {
			$_return['params'] = json_decode($_result['MenuItem']['params'], true);
			$_return['show_title'] = $_result['MenuItem']['show_title'];
			$_return['params']['show_title'] = $_result['MenuItem']['show_title'];
			if ($_result['MenuItem']['show_title'] == 1 && !empty($_result['MenuItem']['page_title'])) {
				$_return['page_title'] = $_result['MenuItem']['page_title'];
				$_return['params']['page_title'] = $_result['MenuItem']['page_title'];
			}
			if ($_result['MenuItem']['show_title'] == 1 && empty($_result['MenuItem']['page_title'])) {
				$_return['page_title'] = $_result['MenuItem']['title'];
				$_return['params']['page_title'] = $_result['MenuItem']['title'];
			}
			if (!empty($_result['MenuItem']['page_meta'])) {
				$_return['description'] = $_result['MenuItem']['page_meta'];
			} else {
				$_return['description'] = Configure::read('MySite.meta_description');
			}
			if (!empty($_result['MenuItem']['page_kw'])) {
				$_return['keywords'] = $_result['MenuItem']['page_kw'];
			} else {
				$_return['keywords'] = Configure::read('MySite.meta_keywords');
			}
			if (empty($_return['params']['page_title'])) {
				$_return['params']['page_title'] = ucwords(Inflector::singularize($_result['MenuItem']['controller']));
			}
			$_return['params']['id'] = $_result['MenuItem']['id'];
		} else {
			$_return['params']['id'] = '0';
			$_return['params'] = '';
		}
		return $_return;
	}
	
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['controller'])) {
			$this->data[$this->alias]['url'] = '';
		}
		return true;
	}

}
