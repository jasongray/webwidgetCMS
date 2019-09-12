<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Modules Controller
 *
 * @property Module $Module
 */
class ModulesController extends AppController {
	
	public function beforeFilter() {
	    parent::beforeFilter();
	}


/**
 * admin_functions method
 * Ajax method to call the module helper, only accepts POST
 * Send the module and function names eg: array('controller' => 'modules', 'action' => 'functions', 'admin' => 'admin', 'module' => 'mod_blah', 'function' => 'test')
 *
 * @param string $mod The module to load
 * @param string $func The function to call in the module helper file
 *
 * @return void
 */
	public function admin_functions($id = false, $module = false, $function = false) {
		if ($this->request->is('post')) {
			$_folder = APP . 'View' . DS . 'Modules' . DS . $module . DS . 'admin';
			if (file_exists($_folder . DS . 'helper.php')) {
				include_once $_folder  . DS . 'helper.php';
				$this->modhelper = new $module;
				if (method_exists($this->modhelper, $function)) {
					if (!empty($id)) {
						$this->request->data['Module']['id'] = $id;
					}
					$out = $this->modhelper->$function($this->request->data);
					$this->set('json', $out);
				}

			}
		}
		$this->render('json');
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Module->recursive = 0;
		$this->paginate = array(
			'order' => array('Module.position' => 'ASC', 'Module.ordering' => 'ASC'),
			'limit' => 20
		);
		$this->set('modules', $this->paginate());
	}
/**
 * admin_cancel method
 *
 * @param string $id
 * @return void
 */
	function admin_cancel($id = null) {
		$this->Session->setFlash(__('Operation cancelled'), 'Flash/admin/info');
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Module->recursive = 0;
			$_m = $this->Module->find('first', array(
				'fields' => array(
					'MAX(Module.ordering) as max_size'
				), 
				'conditions' => array(
					'Module.position' => $this->request->data['Module']['position']
				)
			));
			$this->request->data['Module']['ordering'] = $_m[0]['max_size'] + 1;
			
			$this->Module->create();
			if ($this->Module->save($this->request->data)) {
				$this->Session->setFlash(__('The module has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'edit', $this->Module->id));
			} else {
				$this->Session->setFlash(__('The module could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		}
		
		$positions[__('Admin')] = $this->getModulePositions();
		$_theme = Configure::read('MySite.theme');
		if (!empty($_theme)){
			$positions[$_theme] = $this->getModulePositions('Themed' . DS . $_theme . DS . 'Modules');
		}
		
		$module_files = $this->getModuleFolders();
		
		$this->set(compact('module_files', 'positions'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Module->id = $id;
		if (!$this->Module->exists()) {
			throw new NotFoundException(__('Invalid module'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {		
			if ($this->request->data['Module']['menus'] == 0) {
				$this->request->data['Module']['menuselections'] = $this->getAllMenuItems();
			}
			if ($this->request->data['Module']['menus'] == 1) {
				$this->request->data['Module']['menuselections'] = array();
			}
			$this->request->data['Module']['menuselections'] = implode(',', $this->request->data['Module']['menuselections']);

			$_data = $this->Module->read('params');
			$this->request->data['Module']['params'] = json_encode($_data['Module']['params']);
			//pr($this->request->data);exit;
			if ($this->Module->save($this->request->data)) {
				$this->Session->setFlash(__('The module has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The module could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->Module->read(null, $id);
			$this->request->data['Module']['menuselections'] = explode(',', $this->request->data['Module']['menuselections']);
			if (!is_array($this->request->data['Module']['params'])){
				$this->request->data['Module']['params'] = json_decode($this->request->data['Module']['params'], true);
			}
			$this->request->data['params'] = $this->request->data['Module']['params'];
			unset($this->request->data['Module']['params']);
		}
		if (!isset($this->request->data['Module']['menuselections'])) {
			$selected = array();
		} else {
			$selected = $this->request->data['Module']['menuselections'];
		}
		$positions[__('Admin')] = $this->getModulePositions();
		$_theme = Configure::read('MySite.theme');
		if (!empty($_theme)){
			$positions[$_theme] = $this->getModulePositions('Themed' . DS . $_theme . DS . 'Modules');
		}
		
		$modfiles = $this->getModuleFiles();
		$menus = $this->getModuleMenus();
		$menuselections = $this->getModuleMenusitems();
		$modmenufiles = $this->getModuleMenusFiles();
		
		$this->set(compact('selected', 'positions', 'modfiles', 'menus', 'menuselections', 'modmenufiles'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Module->id = $id;
		if (!$this->Module->exists()) {
			throw new NotFoundException(__('Invalid module'));
		}
		if ($this->Module->delete()) {
			$this->Session->setFlash(__('Module deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Module was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
	
	
/**
 * admin_orderup method
 *
 * @param string $id
 * @return void
 */
	function admin_orderup($id = false){
		if($id){
			$this->ordering(-1, $id);
		}
	}

/**
 * admin_orderdown method
 *
 * @param string $id
 * @return void
 */	
	function admin_orderdown($id = false){
		if($id){
			$this->ordering(1, $id);
		}
	}
	
/**
 * admin_saveorder method
 *
 * @return void
 */
	function admin_saveorder(){
		$this->reorder();
	}
	
	private function ordering( $dir, $id ){
		
		$this->Module->recursive = -1;
		$_this = $this->Module->find('first', array('conditions' => array('Module.id' => $id)));
		
		if($dir < 0){
			$options = array(
				'conditions' => array(
					'Module.ordering < ' . $_this['Module']['ordering'],
					'Module.position' => $_this['Module']['position']
				), 
				'order' => 'Module.ordering DESC',
				'limit' => 1
			);
		}else{
			$options = array(
				'conditions' => array(
					'Module.ordering > ' . $_this['Module']['ordering'],
					'Module.position' => $_this['Module']['position']
				),
				'order' => 'Module.ordering ASC',
				'limit' => 1
			);
		}
		
		$_row = $this->Module->find('first', $options);
		
		if($_row){
			$this->Module->id = $id;
			$this->Module->saveField('ordering', $_row['Module']['ordering']);
			$this->Module->id = $_row['Module']['id'];
			$this->Module->saveField('ordering', $_this['Module']['ordering']);
		}else{
			$this->Module->id = $id;
			$this->Module->saveField('ordering', $_this['Module']['ordering']);
		}
		$this->reorder($_this['Module']['position']);
		$this->redirect(array('action' => 'index'));
			
	}
	
	private function reorder($pos){
		
		$this->Module->recursive = -1;
		$_m = $this->Module->find('all', array(
			'conditions' => array(
				'Module.ordering >= 0',
				'Module.position' => $pos
			),
			'order' => 'Module.ordering'
		));
		
		if($_m){
			
			for ($i=0, $n=count($_m); $i < $n; $i++){
				if($_m[$i]['Module']['ordering'] >= 0){
					if($_m[$i]['Module']['ordering'] != $i+1){
						$_m[$i]['Module']['ordering'] = $i+1;
						$this->Module->id = $_m[$i]['Module']['id'];
						$this->Module->saveField('ordering', $_m[$i]['Module']['ordering']);
					}
				}
			}
			
		}
		
	}
	
	private function getModulePositions($_folder = false) {
		$modules = array();
		$_folder = (!$_folder)? 'Modules': $_folder;
		$_folder = APP . DS . 'View' . DS . $_folder;
		$dir = new Folder($_folder);
		$files = $dir->find('modules.ini');
		if ($files) {
			foreach ($files as $file) {
			    $file = new File($_folder . DS . $file);
			    $contents = $file->read();
			    $file->close(); 
			    $contents = preg_replace('/\/\*(.*)\*\//', '', $contents);
			    $_modules = preg_split("/[\s,]+/", $contents);
			    asort($_modules);
			    foreach ($_modules as $m) {
			    	$modules[$m] = $m;
			    }
			}
		} else {
			$this->Session->setFlash(__('You are missing the modules.ini file. Please create the file with the required modules and save into the ' . $_folder . ' directory.'), 'Flash/admin/error');
		}
		return $modules;
	}
	
	private function getModuleMenusitems() {
		$this->loadModel('Menu');
		$this->Menu->bindModel(
			array('hasMany' => array(
				'MenuItem' => array(
					'conditions' => array('MenuItem.published' => 1),
					'order' => array('MenuItem.lft ASC'),
				)
			))
		);
		$menu = $this->Menu->find('all', array('conditions' => array('Menu.published' => 1)));
		$options = array();
		if ($menu) {
			foreach ($menu as $m) {
				if (!empty($m['MenuItem'])) {
					foreach ($m['MenuItem'] as $mi) {
						if ($mi['parent_id'] != '') {
							$mi['title'] = '__'.$mi['title'];
						}
						$options[$m['Menu']['title']][$mi['id']] = $mi['title'];
					}
				}
			}
		}
		return $options;
	}
	
	private function getModuleFiles() {
		$modules = array();
		$_folder = 'Modules';
		$dir = new Folder(APP . 'View' . DS . $_folder);
		$files = $dir->find('.*\.php');
		if ($files) {
			foreach ($files as $file) {
				$modules[__('Admin')][$file] = preg_replace('/\.php/', '', $file);
			}
		} 
		
		//$_folder = (Configure::read('MySite.theme'))? 'Themed' . DS . Configure::read('MySite.theme') . DS . 'Modules': 'Modules';
		$_folder = 'Modules'; // we are just uploading Modules to the main Module folder... then each theme can customise its own output using the tmpl files in the Modules folder under the Theme
		$dir = new Folder(APP . 'View' . DS . $_folder);
		$files = $dir->find('.*\.php');
		if ($files) {
			foreach ($files as $file) {
				$modules[Configure::read('MySite.theme')][$file] = preg_replace('/\.php/', '', $file);
			}
		} 
		
		return $modules;
		
	}

	private function getModuleFolders() {
		$modules = array();
		$_folder = 'Modules';
		$dir = new Folder(APP . 'View' . DS . $_folder);
		$files = $dir->read();
		if (!empty($files[0])) {  // being the array of directories in the current folder
			foreach ($files[0] as $folder) {
				$modules[__('Admin')][$folder] = preg_replace('/mod_/', '', $folder);
			}
		} 
		
		$_folder = (Configure::read('MySite.theme'))? 'Themed' . DS . Configure::read('MySite.theme') . DS . 'Modules': 'Modules';
		$dir = new Folder(APP . 'View' . DS . $_folder);
		$files = $dir->read();
		if (!empty($files[0])) {  // being the array of directories in the current folder
			foreach ($files[0] as $folder) {
				$modules[Configure::read('MySite.theme')][$folder] = preg_replace('/mod_/', '', $folder);
			}
		} 
		
		return $modules;
		
	}
	
	
	private function getModuleMenus() {
		$this->loadModel('Menu');
		$menu = $this->Menu->find('all', array('conditions' => array('Menu.published' => 1)));
		$options = array();
		if ($menu) {
			foreach ($menu as $m) {
				if (!empty($m['Menu'])) {
					$options[$m['Menu']['id']] = $m['Menu']['title'];
				}
			}
		}
		return $options;
	}
	
	private function getModuleMenusFiles() {
		$modules = array();
		// load admin modules
		$_folder = 'Modules';
		$dir = new Folder(APP . 'View' . DS . $_folder);
		$files = $dir->find('.*menu.*\.ctp');
		if ($files) {
			foreach ($files as $file) {
				$modules[__('Admin')][$file] = preg_replace('/\.ctp/', '', $file);
			}
		}
		
		// themed modules
		$_folder = (Configure::read('MySite.theme'))? 'Themed' . DS . Configure::read('MySite.theme') . DS . 'Modules': 'Modules';
		$dir = new Folder(APP . 'View' . DS . $_folder);
		$files = $dir->find('.*menu.*\.ctp');
		if ($files) {
			foreach ($files as $file) {
				$modules[Configure::read('MySite.theme')][$file] = preg_replace('/\.ctp/', '', $file);
			}
		} 
		return $modules;
		
	}

	
	private function getAllMenuItems() {
		$this->loadModel('Menu');
		$menu = $this->Menu->find('all', array('conditions' => array('Menu.published' => 1)));
		$options = array();
		if ($menu) {
			foreach ($menu as $m) {
				if (!empty($m['MenuItem'])) {
					foreach ($m['MenuItem'] as $mi) {
						$options[] = $mi['id'];
					}
				}
			}
		}
		return $options;
	}

}
