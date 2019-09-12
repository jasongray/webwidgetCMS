<?php
App::uses('AppController', 'Controller');
/**
 * Alerts Controller
 *
 * @property Alerts $Alerts
 */
class AlertsController extends AppController {
	public function beforeFilter() {
	    parent::beforeFilter(); 
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->paginate = array('limit' => 20, 'order' => 'Alert.end_date ASC, Alert.created ASC');
		$this->set('alerts', $this->paginate());
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
		$this->Alert->id = $id;
		if (!$this->Alert->exists()) {
			throw new NotFoundException(__('Invalid alert'));
		}
		$this->set('alert', $this->Alert->read(null, $id));
	}

/**
 * admin_update method
 *
 * @return void
 */
	public function admin_update() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->Alert->id = $this->request->data['Alert']['id'];
			if ($this->Alert->save($this->request->data)) {
				echo json_encode(true);
				return;
			}
			echo json_encode(false);
			return;
		}
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Alert->create();
			if (empty($this->request->data['Alert']['start_date'])) {
				$this->request->data['Alert']['start_date'] = date('Y-m-d H:i:s');
			} else {
				$this->request->data['Alert']['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Alert']['start_date']));
			}
			if (!empty($this->request->data['Alert']['end_date'])) {
				$this->request->data['Alert']['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Alert']['end_date']));
			}
			if ($this->Alert->save($this->request->data)) {
				$this->Session->setFlash(__('The alert has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The alert could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		}
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Alert->id = $id;
		if (!$this->Alert->exists()) {
			throw new NotFoundException(__('Invalid alert'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (empty($this->request->data['Alert']['start_date'])) {
				$this->request->data['Alert']['start_date'] = date('Y-m-d H:i:s');
			} else {
				$this->request->data['Alert']['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Alert']['start_date']));
			}
			if (!empty($this->request->data['Alert']['end_date'])) {
				$this->request->data['Alert']['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Alert']['end_date']));
			}
			if ($this->Alert->save($this->request->data)) {
				$this->Session->setFlash(__('The alert has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The alert could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->Alert->read(null, $id);
		}
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Alert->id = $id;
		if (!$this->Alert->exists()) {
			throw new NotFoundException(__('Invalid alert'));
		}
		if ($this->Alert->delete()) {
			$this->Session->setFlash(__('Alert deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Alert was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
}