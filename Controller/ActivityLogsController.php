<?php
App::uses('AppController', 'Controller');
/**
 * ActivityLogs Controller
 *
 * @property ActivityLog $ActivityLog
 */
class ActivityLogsController extends AppController {
	
	public function beforeFilter() {
	    parent::beforeFilter();
	}
	
/**
 * recent method
 *
 * @return void
 */
	public function admin_recent() {
		$l = $this->ActivityLog->getRecent();
		if ($this->request->is('requested')) {
			return $l;
		} else {
			$this->set(compact('l'));
		}
		
	}
	
/**
 * recentmembers method
 *
 * @return void
 */
	public function admin_recentmembers() {
		$memb = $this->ActivityLog->getMemberlogs(true);
		if ($this->request->is('requested')) {
			return $memb;
		} else {
			$this->set(compact('memb'));
		}
		
	}

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->ActivityLog->recursive = 0;
		$this->paginate = array('order' => array('ActivityLog.created' => 'DESC'));
		$this->set('logs', $this->paginate());
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
 * view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->ActivityLog->id = $id;
		if (!$this->ActivityLog->exists()) {
			throw new NotFoundException(__('Invalid log entry'));
		}
				
		$l = $this->ActivityLog->read(null, $id);
		$this->ActivityLog->saveField('viewed', 1);
		$this->set(compact('l'));
	}

/**
 * index method
 *
 * @return void
 */
	public function admin_members() {
		if ($this->request->is('requested')) {
			return $this->ActivityLog->getMemberlogs();
		} else {
			$this->paginate = array('conditions' => array("ActivityLog.description LIKE 'Member:%'"), 'order' => array('ActivityLog.created' => 'DESC'), 'limit' => 25);
			$logs = $this->paginate();
			$this->set(compact('logs'));
			$this->render('admin_index');
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->ActivityLog->id = $id;
		if (!$this->ActivityLog->exists()) {
			throw new NotFoundException(__('Invalid log entry'));
		}
		if ($this->ActivityLog->delete()) {
			$this->Session->setFlash(__('Log entry deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Log entry was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
}
