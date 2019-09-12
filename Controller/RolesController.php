<?php
App::uses('AppController', 'Controller');
/**
 * Roles Controller
 *
 * @property Role $Role
 */
class RolesController extends AppController {
	
	public function beforeFilter() {
	    parent::beforeFilter(); 
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Role->recursive = 0;
		$this->paginate = array('limit' => 20, 'order' => 'Role.id ASC');
		$this->set('roles', $this->paginate());
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
		$this->Role->id = $id;
		if (!$this->Role->exists()) {
			throw new NotFoundException(__('Invalid role'));
		}
		$this->set('role', $this->Role->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Role->create();
			if ($this->Role->save($this->request->data)) {
				$this->Session->setFlash(__('The role has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The role could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		}
		$parentRoles = $this->Role->ParentRole->find('list');
		$this->set(compact('parentRoles'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Role->id = $id;
		if (!$this->Role->exists()) {
			throw new NotFoundException(__('Invalid role'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Role->save($this->request->data)) {
				$this->Session->setFlash(__('The role has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The role could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->Role->read(null, $id);
		}
		$parentRoles = $this->Role->ParentRole->find('list');
		$this->set(compact('parentRoles'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Role->id = $id;
		if (!$this->Role->exists()) {
			throw new NotFoundException(__('Invalid role'));
		}
		if ($this->Role->delete()) {
			$this->Session->setFlash(__('Role deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Role was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}
}
