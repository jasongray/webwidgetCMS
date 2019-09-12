<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Inflector', 'Utility');
/**
 * Events Controller
 *
 * @property Events $Events
 */
class EventsController extends AppController {

	public function beforeFilter() {
	    parent::beforeFilter();
		$this->Auth->allow(array('index', 'view', 'bydate', 'category', 'upcoming', 'download', 'printout'));
		$this->paginate = array(
			'order' => array('Event.datetime' => 'ASC')
		);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Event->recursive = 0;
		$_conditions = array('Event.published' => 1, 'Event.datetime >=' => date('Y-m-d h:i:s'));
		if (!empty($this->request->params['named']['category'])) {
			if (is_numeric($this->request->params['named']['category'])) {
				$_conditions = array_merge($_conditions, array("Category.id = '{$this->request->params['named']['category']}'"));
			} else {
				$_conditions = array_merge($_conditions, array("LCASE(Category.title) = LCASE('{$this->request->params['named']['category']}')"));
			}
		}
		$this->paginate = array(
			'conditions' => $_conditions,
			'limit' => Configure::read('MySite.bloglimit'),
			'order' => array('Event.datetime' => 'ASC')
		);
		$this->set('events', $this->paginate());
		
		$this->set('categories', $this->Event->Category->find('all', array('conditions' => array('Category.published' => 1, 'Category.type' => 4), 'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC'))));
	}

/**
 * bydate method
 *
 * @return void
 */
	public function bydate() {
		$this->Event->recursive = 0;
		$_conditions = array('Event.published' => 1);

		if ($this->request->is('ajax')) {
			if (isset($this->request->params['pass'][0]) || isset($this->request->params['pass'][1])) {
				if (isset($this->request->params['pass'][0])) {
					$_conditions = array_merge($_conditions, array("YEAR(Event.datetime) = '{$this->request->params['pass'][0]}'"));
				}
				if (isset($this->request->params['pass'][1])) {
					$_conditions = array_merge($_conditions, array("MONTH(Event.datetime) = '{$this->request->params['pass'][1]}'"));
				}
			} else {
				$_conditions = array_merge($_conditions, array("YEAR(Event.datetime) = YEAR(CURDATE())", "MONTH(Event.datetime) = MONTH(CURDATE())"));
			}
			echo json_encode($this->Event->calendarEvents($_conditions));
			$this->render(false);
		} else {
			if (isset($this->request->params['named']['year'])) {
				$_pagetitle['year'] = $this->request->params['named']['year'];
				$_conditions = array_merge($_conditions, array("YEAR(Event.datetime) = '{$this->request->params['named']['year']}'"));
			}
			if (isset($this->request->params['named']['month'])) {
				$_pagetitle['month'] = $this->request->params['named']['month'];
				$_conditions = array_merge($_conditions, array("MONTH(Event.datetime) = '{$this->request->params['named']['month']}'"));
			}
			if (isset($this->request->params['named']['day'])) {
				$_pagetitle['day'] = $this->request->params['named']['day'];
				$_conditions = array_merge($_conditions, array("DAY(Event.datetime) = '{$this->request->params['named']['day']}'"));
			}
			$this->paginate = array(
				'conditions' => $_conditions,
				'limit' => Configure::read('MySite.bloglimit'),
				'order' => array('Event.datetime' => 'ASC')
			);
			if (isset($_pagetitle) && count($_pagetitle) === 3) {
				$date_title = date('l, jS F Y', strtotime(implode('/', $_pagetitle)));
			} else if (isset($_pagetitle) && count($_pagetitle) === 2) {
				$date_title = date('F Y', strtotime($_pagetitle['year'].'/'.$_pagetitle['month'].'/01'));
			} else {
				$date_title = date('Y', strtotime($_pagetitle['year'].'/01/01'));
			}
			$this->set('date_title', $date_title);
			$this->set('events', $this->paginate());
			$this->set('categories', $this->Event->Category->find('all', array('conditions' => array('Category.published' => 1, 'Category.type' => 4), 'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC'))));
		}
	}

/**
 * category method
 *
 * @return void
 */
	public function category($id = null) {
		$this->Event->recursive = 0;
		$page_title = '';
		$_conditions = array('Event.published' => 1);
		if ($id) {
			$_category = $this->Event->Category->read(null, $id);
			if ($_category) {
				$page_title = $_category['Category']['title'];

			}
			$_conditions = array_merge($_conditions, array('Event.category_id' => $id));
		}
		$this->paginate = array(
			'conditions' => $_conditions,
			'limit' => Configure::read('MySite.bloglimit'),
			'order' => array('Event.datetime' => 'ASC')
		);
		$this->set(compact('page_title'));
		$this->set('events', $this->paginate());
		$this->set('categories', $this->Event->Category->find('all', array('conditions' => array('Category.published' => 1, 'Category.type' => 4), 'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC'))));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Event->recursive = 0;
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		$this->set('e', $this->Event->read(null, $id));

		$this->set('referrer', $this->referer());
		
	}

/**
 * print method
 *
 * @param string $id
 * @return void
 */
	public function printout($id = null) {
		$this->layout = 'print';
		$this->Event->recursive = 0;
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		$this->set('e', $this->Event->read(null, $id));

		$this->set('referrer', $this->referer());

		$this->render('view');
	}
	
/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function upcoming() {
		$this->Event->recursive = -1;
		$e = $this->Event->findFeatured(1);
		if ($this->request->is('requested')) {
			return $e;
		} else {
			$this->set(compact('e'));
		}
		
	}

/**
 * download method
 *
 * @param string $id
 * @return void
 */
	public function download($id) {
		$this->autoRender = false;
		$e = $this->Event->read(null, $id);
		if ($e) {
			$path = WWW_ROOT . 'files' . DS . 'events' . DS . $e['Event']['id'] . DS . $e['Event']['file'];
			$this->response->file($path, array(
		        'download' => true,
		        'name' => $e['Event']['file'],
		    ));
		    return $this->response;
		}
	    return false;
	
	}
	
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Event->recursive = 0;
		$this->paginate = array(
			'order' => array('Events.created' => 'DESC'),
			'limit' => 20
		);
		$this->set('events', $this->paginate());
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
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		$this->set('e', $this->Event->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			if (!empty($this->request->data['tags'])) {
				$this->request->data['Event']['tags'] = $this->request->data['tags'];
				unset($this->request->data['tags']);
			}
			if (empty($this->request->data['Event']['user_id'])) {
				$this->request->data['Event']['user_id'] = $this->Session->read('Auth.User.id');
			}
			$this->request->data['Event']['slug'] = Inflector::slug(strtolower($this->request->data['Event']['title']), '-');
			$this->Event->create();
			if ($this->Event->save($this->request->data)) {
				$this->saveImage($this->request->data['Event']['id']);
				$this->saveFile($this->request->data['Event']['id']);
				$this->Session->setFlash(__('The event has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		}
		$this->set('categories', $this->Event->Category->find('list', array('conditions' => array('Category.type' => 4))));
		if (!isset($this->request->data['Event'])) {
			$this->request->data = array('Event' => array('datetime' => time()));
		}
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Event']['slug'] = Inflector::slug(strtolower($this->request->data['Event']['title']), '-');
			if ($this->Event->save($this->request->data)) {
				$this->saveImage($this->request->data['Event']['id']);
				$this->saveFile($this->request->data['Event']['id']);
				$this->Session->setFlash(__('The event has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->Event->read(null, $id);
		}
		$this->set('categories', $this->Event->Category->find('list', array('conditions' => array('Category.type' => 4))));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		if ($this->Event->delete()) {
			$this->Session->setFlash(__('Event deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Event was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
	
	private function saveImage($id = false){
		if($this->request->data['Image']['file']['error'] != 4){
			
			$tempFile = $this->request->data['Image']['file']['tmp_name'];
			$targetPath = WWW_ROOT . 'img/events/'.$id;
			$folder = new Folder();
			if(!is_dir($targetPath)){
				$folder->create($targetPath);
				$folder->chmod($targetPath, 0777, true);
			}
			$___fileinfo = pathinfo($this->request->data['Image']['file']['name']);
			$__data['file'] = time() . md5($this->request->data['Image']['file']['name']) . '.' . strtolower($___fileinfo['extension']);
			$targetFile =  str_replace('//','/',$targetPath) . '/' . $__data['file'];
			if(move_uploaded_file($tempFile,$targetFile)){
				$this->Event->saveField('image', $__data['file']);
			}else{
				$_result = '<p class="error">Failed to move uploaded file.</p>';
			}
		}
		
	}
	
	private function saveFile($id = false){
		if($this->request->data['File']['file']['error'] != 4){
			
			$tempFile = $this->request->data['File']['file']['tmp_name'];
			$targetPath = WWW_ROOT . 'files/events/'.$id;
			$folder = new Folder();
			if(!is_dir($targetPath)){
				$folder->create($targetPath);
				$folder->chmod($targetPath, 0777, true);
			}
			$___fileinfo = pathinfo($this->request->data['File']['file']['name']);
			$newfile = Inflector::slug($___fileinfo['filename'], '-') . '.' . strtolower($___fileinfo['extension']);
			$targetFile =  str_replace('//','/',$targetPath) . '/' . $newfile;
			if(move_uploaded_file($tempFile,$targetFile)){
				$this->Event->saveField('file', $newfile);
			}else{
				$_result = '<p class="error">Failed to uploaded file.</p>';
			}
		}
		
	}
	
	function admin_removeImage( $id = false ){
		$this->layout = 'ajax';
		$this->render(false);
		$this->Session->setFlash(__('Image was not removed', true), 'Flash/admin/error');
		if ($id) {
			$_img = $this->Event->read('image', $id);
			$file = new File(WWW_ROOT . 'img/events/' . $id . '/' . $_img['Event']['image']);
			if($_img && $file->exists()){
				$file->delete();
			}
			if ($this->Event->saveField('image', '')) {
				$this->Session->setFlash(__('Image was removed', true), 'Flash/admin/success');
			}
		}
		$this->redirect(array('action' => 'edit', $id));
		
	}
	
	function admin_removeFile( $id = false ){
		$this->layout = 'ajax';
		$this->render(false);
		$this->Session->setFlash(__('File was not removed', true), 'Flash/admin/error');
		if ($id) {
			$_img = $this->Event->read('file', $id);
			$file = new File(WWW_ROOT . 'files/events/' . $id . '/');
			if($_img && $file->exists($_img['Event']['file'])){
				$file->delete($_img['Event']['file']);
			}
			if ($this->Event->saveField('file', '')) {
				$this->Session->setFlash(__('File was removed', true), 'Flash/admin/success');
			}
		}
		$this->redirect(array('action' => 'edit', $id));
		
	}
}
