<?php
App::uses('AppController', 'Controller');
/**
 * Tags Controller
 *
 * @property Tag $Tag
 */
class TagsController extends AppController {

	function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow(array('get', 'cloud', 'add'));
	}
	
	
	public function cloud(){
		return $this->Tag->find('all', array('order' => array('Tag.count DESC')));
	}

/**
 * admin_getmethod
 *
 * @return void
 */
	public function get() {
		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$conditions = array();
			if (isset($this->request->query['term']) && !empty($this->request->query['term'])) {
				$conditions = array("tag LIKE '%{$this->request->query['term']}%'");
			}
			$tags = $this->Tag->find('all', array('conditions' => $conditions));
			if ($tags) {
				foreach($tags as $tag) {
					$_tag[] = array('id' => $tag['Tag']['tag'], 'label' => $tag['Tag']['tag'], 'value' => $tag['Tag']['tag']);
				}
				echo json_encode($_tag);
			}
		} else if ($this->request->is('requested')) {
			$this->autoRender = false;
			return $this->Tag->find('all');
		} else {
			$this->set('tags', $this->Tag->find('all'));
		}
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function add() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			if (!$tag = $this->Tag->find('first', array('conditions' => array('tag' => $this->request->data['tag'])))) {
				$this->Tag->create();
				if ($this->Tag->save($this->request->data)) {
					return true;
				} else {
					return false;
				}
			} else {
				$tag['Tag']['count'] = $tag['Tag']['count'] + 1;
				if ($this->Tag->save($tag)) {
					return true;
				} else {
					return false;
				}
			}
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Tag->recursive = 0;
		$this->paginate = array('order' => array('Tag.created' => 'DESC'));
		$this->set('tags', $this->paginate());
	}

/**
 * cancel method
 *
 * @param string $id
 * @return void
 */
	public function admin_cancel($id = null) {
		$this->Session->setFlash(__('Operation cancelled'), 'Flash/admin/info');
		$this->redirect(array('action' => 'index'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Tag->id = $id;
		if (!$this->Tag->exists()) {
			throw new NotFoundException(__('Invalid tag id'));
		}
		if ($this->Tag->delete()) {
			$this->Session->setFlash(__('Tag deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Tag was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}

}
