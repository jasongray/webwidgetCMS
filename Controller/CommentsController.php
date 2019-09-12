<?php
App::uses('AppController', 'Controller');
/**
 * Comments Controller
 *
 * @property Comment $Comment
 */
class CommentsController extends AppController {
	
	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow(array('add', 'latest'));
	}
	
	public function add(){
		if ($this->request->is('post')) {
			$this->Comment->create();
			if ($this->Comment->validates()) {
				if ($this->Comment->save($this->request->data)) {
					$this->Session->setFlash(__('Your comment has been saved'), 'Flash/success', array(), 'Comments');
					$this->log(__('Comment added by user.') . ' ' . $this->Comment->getLastInsertID(), 'ok', 'activity');
					if ($this->RequestHandler->isAjax()) {
						echo json_encode(array('true'));
					} else {
						$this->redirect(array('action' => 'index'));
					}
				} else {
					$this->Session->setFlash(__('Your comment could not be saved. Please, try again.'), 'Flash/error', array(), 'Comments');
					if ($this->RequestHandler->isAjax()) {
						echo json_encode(array('false'));
					} else {
						$this->redirect(array('action' => 'index'));
					}
				}
			} else {
				$this->Session->setFlash(__('Your comment could not be saved. Please, try again.'), 'Flash/error', array(), 'Comments');
				if ($this->RequestHandler->isAjax()) {
					echo json_encode(array('false'));
				} else {
					$this->redirect(array('action' => 'index'));
				}
			}
		}
		if ($this->RequestHandler->isAjax()) {
			$this->render(false);
		}
	}
	
	public function latest() {
		$this->Comment->recursive = 0;
		if ($this->request->is('requested')) {
			return $this->Comment->newcomments();
		} else {
			$this->set('comments', $this->Comment->newcomments());
		}
	}
	

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Comment->recursive = 0;
		$this->paginate = array(
			'order' => array('Comment.created' => 'DESC'),
			'limit' => 20
		);
		$this->set('comments', $this->paginate());
	}

/**
 * admin_approve method
 *
 * @param string $key
 * @return boolean
 */	
	public function admin_approve($key = null) {
		if ($key){
			if ($this->Comment->isApproved($key)) {
				$this->Session->setFlash(__('Comment has been approved'), 'Flash/admin/success');
			} else {
				$this->Session->setFlash(__('Comment was not approved'), 'Flash/admin/error');
			}
		}
		if ($this->RequestHandler->isAjax()) {
			$this->render(false);
		} else {
			$this->redirect(array('action' => 'index'));
		}
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
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		$c = $this->Comment->read(null, $id);
		$this->set(compact('c'));
		
		if($c['Comment']['viewed'] == 0){
			$this->Comment->id = $c['Comment']['id'];
			$this->Comment->saveField('viewed', 1);
		}
		
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		if ($this->Comment->delete()) {
			$this->Session->setFlash(__('Comment deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('News was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
	
}