<?php
App::uses('AppModel', 'Model');
/**
 * Module Model
 *
 */
class Module extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	
	
	public function getModules($position = false, $tpl = 'default', $params = array()) {
		if ($position) {
			$cache = Cache::read('getModules_' . $position . '_' . $tpl, 'longterm');
			if (!$cache) {
				if (!isset($params['params']['id'])) {
					$params['params']['id'] = 0;
				}
				$_conditions = array("IF(Module.menus = 2, FIND_IN_SET({$params['params']['id']}, Module.menuselections), 1 = 1)");
				$_conditions = array_merge(array('Module.published' => 1, 'Module.position' => $position), $_conditions);
				$result = $this->find('all', array(
					'fields' => array(
						'title',
						'show_title',
						'params',
						'idclass',
						'class',
						'header',
						'header_class',
						'module_file',
						),
					'conditions' => $_conditions, 
					'order' => 'Module.ordering ASC'));
				Cache::write('getModules_' . $position . '_' . $tpl, $result, 'longterm');
				return $result;
			} else {
				return $cache;
			}
		}
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['Module']['module_file'])) {
			$helper = $this->loadHelper($this->data['Module']['module_file']);
			if ($helper && method_exists($helper, 'save')) {
				$this->data = $helper->save($this->data);
			}
			
			if (isset($this->data['params']) && is_array($this->data['params'])) {
				$_array = json_decode($this->data['Module']['params'], true);
				if (!empty($_array) && is_array($_array)) {
					$this->data['Module']['params'] = json_encode(array_replace($_array, $this->data['params']));
				} else {
					$this->data['Module']['params'] = json_encode($this->data['params']);
				}
				unset($this->data['params']);
			}

			if (is_array($this->data['Module']['params'])) {
				$this->data['Module']['params'] = json_encode($this->data['Module']['params']);
			}
		}
		return true;
	}
	
/**
 * After find method
 * Called after the data is retrieved from the database
 *
 * @param array $results
 * @param bool $primary
 * @return bool
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Module']['params']) && !empty($val['Module']['params'])) {
				$results[$key]['Module']['params'] = json_decode($val['Module']['params'], true);
			}
		}
		return $results;
	}

	private function loadHelper($_m) {
		$_path = APP . 'View' . DS;
		$_folder = (Configure::read('MySite.theme'))? 'Themed' . DS . Configure::read('MySite.theme') . DS . 'Modules': 'Modules';
		$_mod_folder = DS . $_m . DS . 'admin' . DS;
		if (file_exists($_path . $_folder . $_mod_folder . 'helper.php')) {
			include_once $_path . $_folder . $_mod_folder . 'helper.php';
			return new $_m;
		} else if (file_exists($_path . 'Modules' . $_mod_folder . 'helper.php')) {
			include_once $_path . 'Modules' . $_mod_folder . 'helper.php';
			return new $_m;
		}
		return false;
	}
	
}
