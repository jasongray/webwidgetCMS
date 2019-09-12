<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Categories Controller
 *
 * @property Category $Category
 */
class CategoriesController extends AppController {

	function beforeFilter() {
	    parent::beforeFilter();
		$this->Auth->allow(array('index', 'view', 'genlist')); 
		$this->paginate = array(
			'order' => array('Category.created' => 'DESC')
		);
	}
	
	function index(){
		$this->Category->recursive = 0;
		$this->paginate = array(
			'conditions' => array('Category.published' => 1),
			'limit' => $this->_params['catlimit'],
			'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC')
		);
		$this->set('categories', $this->paginate());
	}
	
	function view($id = null, $slug = null){
		$this->Category->recursive = -2;
		if (!$id) {
			$this->Session->setFlash(__('Invalid category', true), 'Flash/admin/error');
			$this->redirect($this->referer());
		}
		
		$category = $this->Category->read(null, $id);
		$this->set('title_for_layout', $category['Category']['title']);
		$this->set('c', $category);
		
		// Product category
		if ($category['Category']['type'] == 1) {
			$this->loadModel('Product');
			$this->set('products', $this->Product->find('all', array(
				'conditions' => array(
					'Product.category_id' => $id, 
					'Product.published' => 1
				),
				'limit' => $this->_params['catlimit'],
				'order' => 'Product.ordering ASC'))
			);
		}
		
		// Content category
		if ($category['Category']['type'] == 3) {
			$this->loadModel('Page');
			$this->set('pages', $this->Page->find('all', array(
				'conditions' => array(
					'Page.category_id' => $id,
					'Page.published' => 1
				),
				'limit' => $this->_params['catlimit'],
				'order' => 'Page.created DESC'))
			);
		}
		
		// Blog category
		if ($category['Category']['type'] == 2) {
			$this->loadModel('News');
			$this->News->virtualFields = array('comments' => 'SELECT COUNT(Comment.id) FROM comments AS Comment WHERE Comment.news_id = News.id AND Comment.status = 1');
			$this->set('posts', $this->News->find('all', array(
				'conditions' => array(
					'News.category_id' => $id,
					'News.published' => 1
				),
				'limit' => $this->_params['catlimit'],
				'order' => 'News.created DESC'))
			);

		}

		$this->render('index');
				
	}
	
	function genlist(){
		$this->autoRender = false;
		$this->Category->recursive = 0;
		$conditions = array(
			'conditions' => array('Category.published' => 1),
			'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC')
		);
		return $this->Category->find('all', $conditions);
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Category->recursive = 0;
		$this->paginate = array(
			'order' => 'Category.type, Category.lft ASC',
			'limit' => 25
		);
		$this->set('categories', $this->paginate());
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
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid category'));
		}
		$this->set('category', $this->Category->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->request->data['Category']['slug'] = Inflector::slug(strtolower($this->request->data['Category']['title']), '-');
			$this->Category->create();
			if ($this->Category->save($this->request->data)) {
				$this->saveImage($this->request->data['Category']['id']);
				$this->Session->setFlash(__('The category has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		}
		$parents = $this->Category->generateTreeList();
		$this->set(compact('parents'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid category'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Category']['slug'] = Inflector::slug(strtolower($this->request->data['Category']['title']), '-');
			if ($this->Category->save($this->request->data)) {
				$this->saveImage($this->request->data['Category']['id']);
				$this->Session->setFlash(__('The category has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->Category->unBindModel(array('hasMany' => array('Page', 'News', 'Product')));
			$this->request->data = $this->Category->read(null, $id);
		}
		$parents = $this->Category->generateTreeList();
		$this->set(compact('parents'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid category'));
		}
		$data = $this->Category->read(null, $id);
		if ($file = new File(APP . DS . 'webroot' . DS . 'img' . DS . 'categories' . DS . $data['Category']['image'])) {
			$file->delete();
		}
		if ($this->Category->delete()) {
			$this->Session->setFlash(__('Category deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Category was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * admin_orderup method
 *
 * @param string $id
 * @return void
 */
	function admin_orderup($id = false, $delta = 1){
		$this->autoRender = false;
		if($id){
			$this->Category->moveUp($id, $delta);
		}
		$this->redirect(array('action' => 'index'));
	}

/**
 * admin_orderdown method
 *
 * @param string $id
 * @return void
 */	
	function admin_orderdown($id = false, $delta = 1){
		$this->autoRender = false;
		if($id){
			$this->Category->moveDown($id, $delta);
		}
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * admin_saveorder method
 *
 * @return void
 */
	function admin_saveorder(){
		$this->reorder();
	}
	
	private function saveImage($id = false){
		
		if($this->data['Image']['file']['error'] != 4){
			$temp = $this->data['Image']['file']['tmp_name'];
			$tdir = WWW_ROOT . 'img' . DS . 'categories';
			$dir = new Folder($tdir, true, 0766);
			$target = time() . '_' . $this->data['Image']['file']['name'];
			if(move_uploaded_file($temp, $tdir . DS . $target)){
				$this->Category->saveField('image', $target);
			}else{
				$_result = '<p class="error">Failed to move uploaded file.</p>';
			}
		}
		
	}
	
	function admin_removeImage( $id = false ){
		$this->layout = 'ajax';
		$this->render(false);
		$this->Session->setFlash(__('Image was not removed', true), 'Flash/admin/error');
		if ($id) {
			$data = $this->Category->read('image', $id);
			if ($file = new File(APP . DS . 'webroot' . DS . 'img' . DS . 'categories' . DS . $data['Category']['image'])) {
				$file->delete();
			}
			if ($this->Category->saveField('image', '')) {
				$this->Session->setFlash(__('Image was removed', true), 'Flash/admin/success');
			}
		}
		$this->redirect(array('action' => 'edit', $id));
		
	}
}
