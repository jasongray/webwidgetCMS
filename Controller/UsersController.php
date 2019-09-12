<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {
	
	var $components = array('Update');

	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow(array('index', 'members_login', 'members_logout', 'admin_login', 'admin_logout'));
	}
	
	public function members_login() {
		if ($this->request->is('post')) {
			$this->Session->destroy('unverified');
			if ($this->Auth->login()) {
				if (Configure::read('MySite.require_verification') && $this->Session->read('Auth.Member.active') == 0) {
					$this->log(sprintf(__('Member: %s not active and requires verification.'), $this->Session->read('Auth.Member.username')), 'warning', 'activity');
					$this->Session->setFlash(__('Your account is not active. Please email the site admin.'), 'Flash/warning');
					$this->Session->write('unverified', 'true');
					$this->redirect($this->Auth->logout());
					return;
				}
				$this->User->id = $this->Session->read('Auth.Member.id');
				$this->User->saveField('last_login', date('Y-m-d H:i:s'));
				$this->log(sprintf(__('Member: %s logged in.'), $this->Session->read('Auth.Member.username')), 'success', 'activity');
				$this->Session->delete('Message.auth');
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'prefix' => 'members'));
			} else {
				$this->Session->setFlash(__('Incorrect Username and/or Password.'), 'Flash/error');
				$this->log(sprintf(__('Member: attempted login failed, using username "%s".'), $this->request->post['username']), 'error', 'activity');
				$this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'members'));
			}
		}
		$this->request->data = null;
	}
	
	public function members_logout () {
		$this->log(sprintf(__('Member: %s logged out.'), $this->Session->read('Auth.Member.username')), 'info', 'activity');
		$this->Cookie->destroy('Auth.Member');
		$this->Session->setFlash('You are now logged out!', 'Flash/info');
		$this->redirect($this->Auth->logout());
	}
	
	public function members_dashboard () {
		$this->loadModel('Project');
		$this->set('projects', $this->Project->getMemberProjects($this->Session->read('Auth.Member')));
	}
	
	public function members_edit ($id = null) {
		if (!$id) {
			$id = $this->Session->read('Auth.Member.id');
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}
	
	public function admin_login() {
		$this->layout = 'login';
		$this->theme = '';
		if ($this->request->is('post')) {
			$this->Session->destroy('unverified');
			if ($this->Auth->login()) {
				if (!empty($this->request->data) && $this->request->data['User']['remember']) {
					$cookie = array();
					$cookie['username'] = $this->request->data['User']['username'];
					$cookie['password'] = $this->request->data['User']['password'];
					$cookie['ipaddress'] = $_SERVER['REMOTE_ADDR'];
					$cookie['useragent'] = $_SERVER['HTTP_USER_AGENT'];
					$this->Cookie->write('Auth.User', $cookie, true);
					unset($this->request->data['User']['remember']);
				}
				if (Configure::read('MySite.require_verification') && $this->Session->read('Auth.User.active') == 0) {
					$this->Session->setFlash(__('Your account is not active. Please email the site admin.'), 'Flash/warning');
					$this->Session->write('unverified', 'true');
					$this->redirect($this->Auth->logout());
					return;
				}
				$this->User->id = $this->Session->read('Auth.User.id');
				$this->User->saveField('last_login', date('Y-m-d H:i:s'));
				$this->log(__('User logged in.'), 'info', 'activity');
				$this->Session->delete('Message.auth');
				if ($this->Session->read('Auth.User.role_id') == 1) {
					$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin'));
				} else {
					$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
				}
			} else {
				$this->Session->setFlash(__('Incorrect Username and/or Password.'), 'Flash/error');
				$this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
		}
		if (empty($this->request->data)) {
			$cookie = $this->Cookie->read('Auth.User');
			if (!is_null($cookie) && $cookie['ipaddress'] === $_SERVER['REMOTE_ADDR'] && $cookie['useragent'] === $_SERVER['HTTP_USER_AGENT']) {
				$user = $this->User->identify($cookie);
				if ($this->Auth->login($user)) {
					$this->Session->destroy('Message.Auth');
					$this->log(__('User has been authenticated via cookie and is now logged in'), 'info', 'activity');
					$this->User->id = $this->Session->read('Auth.User.id');
					$this->User->saveField('last_login', date('Y-m-d H:i:s'));
					if ($this->Session->read('Auth.User.role_id') == 1) {
						$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin'));
					} else {
						$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
					}
				} else {
					$this->log(__('User attempted to gain access with an invalid cookie'), 'important', 'activity');
					$this->Cookie->destroy('Auth.User');
					$this->Session->setFlash(__('Unfortunately you need to re-login.'));
					$this->redirect('login');
				}
			}
		}
	}
	
	public function admin_logout(){
		$this->Cookie->destroy('Auth.User');
		$this->Session->setFlash('You are now logged out!', 'Flash/success');
		$this->redirect($this->Auth->logout());
	}
	

/**
 * admin_dashboard method
 *
 * @return void
 */
	function admin_dashboard(){
		$this->set('title_for_layout', 'Dashboard');
		
		$this->loadModel('Report');
		$svr = $this->Report->getServerInfo();
		$this->set(compact('svr'));
		
		$this->set('update', $this->Update->check());
		
	}
	
	
	public function admin_graph() {
		$graph = array();
		if ($this->request->is('ajax')) {
			$graph = $this->User->getGraphInfo();
		}
		$this->set(compact('graph'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->paginate = array('limit' => 20, 'order' => 'User.surname ASC');
		$this->set('users', $this->paginate());
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->saveAvatar($this->request->data['User']['id']);
				$this->Session->setFlash(__('The user has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->saveAvatar($this->request->data['User']['id']);
				$this->Session->setFlash(__('The user has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$data = $this->User->read(null, $id);
		if ($file = new File(APP . DS . 'webroot' . DS . 'img' . DS . 'users' . DS . $data['User']['avatar'])) {
			$file->delete();
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * admin_cancel method
 *
 * @param string $id
 * @return void
 */
	function admin_cancel($id = null) {
		$this->Session->setFlash(__('Operation cancelled', true), 'Flash/admin/info');
		$this->redirect(array('action' => 'index'));
	}

/**
 * removeAvatar method
 *
 * @param string $id
 * @return void
 */
	function admin_removeAvatar( $id = false ){
		$this->layout = 'ajax';
		$this->render(false);
		$this->Session->setFlash(__('User avatar was not removed', true), 'Flash/admin/warning');
		if ($id) {
			$data = $this->User->read('avatar', $id);
			if ($file = new File(APP . DS . 'webroot' . DS . 'img' . DS . $data['User']['avatar'])) {
				$file->delete();
			}
			if ($this->User->saveField('avatar', '')) {
				$this->Session->setFlash(__('Image was removed', true), 'Flash/admin/success');
			}
		}
		$this->redirect(array('action' => 'edit', $id));
	}
	
/**
 * saveAvatar method
 *
 * @param string $id
 * @return void
 */	
	private function saveAvatar($id = false){
		if(isset($this->request->data['Image']['file']) && $this->request->data['Image']['file']['error'] != 4){
			$temp = $this->data['Image']['file']['tmp_name'];
			$tdir = WWW_ROOT . 'img' . DS . 'users';
			$dir = new Folder($tdir, true, 0766);
			$file = pathinfo($this->request->data['Image']['file']['name']);
			$target = time() . md5($this->data['Image']['file']['name']) . '.' . $file['extension'];
			if(move_uploaded_file($temp, $tdir . DS . $target)){
				$this->User->saveField('avatar', 'users' . DS . $target);
				$this->Session->write('Auth.User.avatar', 'users' . DS . $target);
			} else {
				$this->Session->setFlash(__('User avatar was not uploaded', true), 'Flash/admin/warning');
			}
			
		}
	}
	
	
	private function getErr() {
		$message = '';
		if(isset($this->request->data['Image']['file'])  && $this->request->data['Image']['file']['error'] != 4) {
			$max_upload = (int)(ini_get('upload_max_filesize'));
			$max_post = (int)(ini_get('post_max_size'));
			$memory_limit = (int)(ini_get('memory_limit'));
			$upload_mb = min($max_upload, $max_post, $memory_limit);
			
			switch ($this->request->data['Image']['file']['error']) {
	            case 1:
	                $message = __("The file you attempted to upload exceeds the maximum file size of ") . $upload_mb . 'MB';
	                break;
	            case 2:
	                $message = __("The file you attempted to upload exceeds the maximum file size of ") . $upload_mb . 'MB';
	                break;
	            case 3:
	                $message = __("The file was only partially uploaded");
	                break;
	            case 5:
	                $message = __("No file was uploaded");
	                break;
	            case 6:
	                $message = __("Missing a temporary folder");
	                break;
	            case 7:
	                $message = __("Failed to write file to disk");
	                break;
	            case 8:
	                $message = __("File upload stopped by extension");
	                break;
	            default:
	                $message = __("An unknown error occurred");
	                break;
	        } 
		}
		return $message;
	}
		
}
