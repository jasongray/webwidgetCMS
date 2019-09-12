<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * News Controller
 *
 * @property News $News
 */
class NewsController extends AppController {

	public function beforeFilter() {
	    parent::beforeFilter();
		$this->Auth->allow(array('index', 'view', 'featured', 'tagged', 'author'));
		$this->paginate = array(
			'order' => array('News.start_publish' => 'DESC'),
			'limit' => 12,
		);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->News->recursive = 0;
		$_conditions = array('News.published' => 1, 'IF(News.end_publish IS NOT NULL, NOW() BETWEEN News.start_publish AND News.end_publish, 1)');
		if (!empty($this->request->params['category'])) {
			if (is_numeric($this->request->params['named']['category'])) {
				$_conditions = array_merge($_conditions, array("Category.id = '{$this->request->params['named']['category']}'"));
			} else {
				$_conditions = array_merge($_conditions, array("LCASE(Category.title) = LCASE('{$this->request->params['named']['category']}')"));
			}
		}
		if (!empty($this->request->params['author'])) {
			$_conditions = array_merge($_conditions, array('News.author' => $this->request->params['named']['author']));
		}
		$this->News->virtualFields = array('comments' => 'SELECT COUNT(Comment.id) FROM comments AS Comment WHERE Comment.news_id = News.id AND Comment.status = 1');
		$this->paginate = array(
			'conditions' => $_conditions,
			'limit' => Configure::read('MySite.bloglimit'),
			'order' => array('News.start_publish' => 'DESC')
		);
		
		if (isset($this->request->params['requested'])) {
			return $this->paginate();
		} else {
			$this->set('posts', $this->paginate());
			$this->set('categories', $this->News->Category->find('all', array('conditions' => array('Category.published' => 1), 'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC'))));
		}
	}

/**
 * featured posts method
 *
 * @return void
 */
	public function featured() {
		$this->News->recursive = 0;
		$_conditions = array('News.published' => 1, 'News.featured' => 1, 'IF(News.end_publish IS NOT NULL, NOW() BETWEEN News.start_publish AND News.end_publish, 1)');

		$this->News->virtualFields = array('comments' => 'SELECT COUNT(Comment.id) FROM comments AS Comment WHERE Comment.news_id = News.id AND Comment.status = 1');
		$this->paginate = array(
			'conditions' => $_conditions,
			'limit' => Configure::read('MySite.bloglimit'),
			'order' => array('News.start_publish' => 'DESC')
		);

		if (isset($this->request->params['requested'])) {
			return $this->paginate();
		} else {
			$this->set('posts', $this->paginate());
			$this->set('categories', $this->News->Category->find('all', array('conditions' => array('Category.published' => 1), 'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC'))));
		}
	}

/**
 * tagged posts method
 *
 * @return void
 */
	public function tagged() {
		$this->News->recursive = 0;
		$_conditions = array('News.published' => 1, 'IF(News.end_publish IS NOT NULL, NOW() BETWEEN News.start_publish AND News.end_publish, 1)');
		if (!empty($this->request->params['tag'])) {
			$_conditions = array_merge($_conditions, array('MATCH(News.tags) AGAINST(? IN BOOLEAN MODE)' => urlencode(strtolower($this->request->params['tag']))));
			$this->set('tag', $this->request->params['tag']);
		}
		$this->News->virtualFields = array('comments' => 'SELECT COUNT(Comment.id) FROM comments AS Comment WHERE Comment.news_id = News.id AND Comment.status = 1');
		$this->paginate = array(
			'conditions' => $_conditions,
			'limit' => Configure::read('MySite.bloglimit'),
			'order' => array('News.start_publish' => 'DESC')
		);

		if (isset($this->request->params['requested'])) {
			return $this->paginate();
		} else {
			$this->set('posts', $this->paginate());
			$this->set('categories', $this->News->Category->find('all', array('conditions' => array('Category.published' => 1), 'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC'))));
		}
	}

/**
 * author posts method
 *
 * @return void
 */
	public function author() {
		$this->News->recursive = 0;
		$_conditions = array('News.published' => 1, 'IF(News.end_publish IS NOT NULL, NOW() BETWEEN News.start_publish AND News.end_publish, 1)');
		if (!empty($this->request->params['author'])) {
			$_conditions = array_merge($_conditions, array('News.author' => urldecode(strtolower($this->request->params['author']))));
			$this->News->User->virtualFields = array('name' => "CONCAT(User.firstname, ' ', User.surname)");
			$u = $this->News->User->find('first', array('fields' => array('name', 'User.profile', 'User.display', 'Role.name'), 'conditions' => array('name' => urldecode($this->request->params['author']))));
			if (empty($u)) {
				$u['User']['name'] = ucwords($this->request->params['author']);
				$u['User']['display'] = '';
				$u['User']['profile'] = '';
				$u['Role']['name'] = __('Contributor');
			}
			$this->set(compact('u'));
		}
		$this->News->virtualFields = array('comments' => 'SELECT COUNT(Comment.id) FROM comments AS Comment WHERE Comment.news_id = News.id AND Comment.status = 1');
		$this->paginate = array(
			'conditions' => $_conditions,
			'limit' => Configure::read('MySite.bloglimit'),
			'order' => array('News.start_publish' => 'DESC')
		);

		if (isset($this->request->params['requested'])) {
			return $this->paginate();
		} else {
			$this->set('posts', $this->paginate());
			$this->set('categories', $this->News->Category->find('all', array('conditions' => array('Category.published' => 1), 'order' => array('Category.ordering' => 'ASC', 'Category.created' => 'DESC'))));
		}
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null, $slug = null) {
		$this->News->id = $id;
		if (!$this->News->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		$this->News->virtualFields = array('comments' => 'SELECT COUNT(Comment.id) FROM comments AS Comment WHERE Comment.news_id = News.id AND Comment.status = 1');
		$p = $this->News->read(null, $id);
		$this->set('title_for_layout', $p['News']['title']);
		$this->set(compact('p'));
		
		$this->loadModel('Comment');
		$this->set('comments', $this->Comment->getCreationComments($id));
		
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->News->recursive = 0;
		$this->paginate = array(
			'order' => array('News.created' => 'DESC'),
			'limit' => 20
		);
		$this->set('news', $this->paginate());
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
 * admin_update method
 *
 * @return void
 */
	public function admin_update() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->News->id = $this->request->data['News']['id'];
			if ($this->News->save($this->request->data)) {
				echo json_encode(true);
				return;
			}
			echo json_encode(false);
			return;
		}
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->News->id = $id;
		if (!$this->News->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		$this->set('news', $this->News->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			if (!empty($this->request->data['tags'])) {
				$this->request->data['News']['tags'] = $this->request->data['tags'];
				unset($this->request->data['tags']);
			}
			$this->request->data['News']['slug'] = Inflector::slug(strtolower($this->request->data['News']['title']), '-');
			$this->request->data['News']['user_id'] = $this->Session->read('Auth.User.id');
			$this->News->create();
			if ($this->News->save($this->request->data)) {
				$this->saveImage($this->request->data['News']['id']);
				$this->Session->setFlash(__('The news has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The news could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		}
		$this->set('categories', $this->News->Category->find('list', array('conditions' => array('Category.type' => 2))));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->News->id = $id;
		if (!$this->News->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (!empty($this->request->data['tags'])) {
				$this->request->data['News']['tags'] = $this->request->data['tags'];
				unset($this->request->data['tags']);
			}
			$this->request->data['News']['slug'] = Inflector::slug(strtolower($this->request->data['News']['title']), '-');
			if ($this->News->save($this->request->data)) {
				$this->saveImage($this->request->data['News']['id']);
				$this->Session->setFlash(__('The news has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The news could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->News->read(null, $id);
		}
		$this->set('categories', $this->News->Category->find('list', array('conditions' => array('Category.type' => 2))));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->News->id = $id;
		if (!$this->News->exists()) {
			throw new NotFoundException(__('Invalid news'));
		}
		$data = $this->News->read(null, $id);
		if ($file = new File(APP . DS . 'webroot' . DS . 'img' . DS . 'articles' . DS . $data['News']['image'])) {
			$file->delete();
		}
		if ($this->News->delete()) {
			$this->Session->setFlash(__('News deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('News was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
	
	private function saveImage($id = false){
		
		if($this->data['Image']['file']['error'] != 4){
			$temp = $this->data['Image']['file']['tmp_name'];
			$tdir = WWW_ROOT . 'img' . DS . 'articles';
			$dir = new Folder($tdir, true, 0766);
			$target = time() . '_' . Inflector::slug(strtolower($this->data['Image']['file']['name']), '-');
			if(move_uploaded_file($temp, $tdir . DS . $target)){
				$this->News->saveField('image', $target);
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
			$data = $this->News->read('image', $id);
			if ($file = new File(APP . DS . 'webroot' . DS . 'img' . DS . 'articles' . DS . $data['News']['image'])) {
				$file->delete();
			}
			if ($this->News->saveField('image', '')) {
				$this->Session->setFlash(__('Image was removed', true), 'Flash/admin/success');
			}
		}
		$this->redirect(array('action' => 'edit', $id));
		
	}
	
}
