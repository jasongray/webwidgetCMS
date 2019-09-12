<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';

/**
 * Default helper
 *
 * @var array
 */
	public $helpers = array('Html', 'Session');

	public $components = array('ProStore');

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
	
	public $cacheAction = array(
	    'display' => '5 days'
	);
	
	
	function beforeFilter() {
	    parent::beforeFilter();
		$this->Auth->allow(array('display', 'home'));
	}

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display($id = false, $slug = false) {
		$path = func_get_args();
		$render = 'default';
		$count = count($path);
		if ($count > 1) {
			$p = $this->Page->getPages($id);
			$render = (!empty($p['Page']['template']))? $p['Page']['template']: $render;
			if (!empty($p)) {
				$this->set('title_for_layout', $p['Page']['title']);
			}
			$this->set(compact('p'));
		} else {
			$p = $this->Page->getPages();
			$render = (!empty($p['Page']['template']))? $p['Page']['template']: $render;
			$this->set(compact('p'));
		}
		$this->render($render);
		
	}
	

	public function home() {
		$this->set('title_for_layout', __('Home'));
		$this->set('p', $this->Page->getHomePages());
		
	}
	
	
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Page->recursive = 0;
		$this->paginate = array(
			'order' => array('Page.id' => 'ASC'),
			'limit' => 20,
		);
		$this->set('pages', $this->paginate());
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
		$this->Page->id = $id;
		if (!$this->Page->exists()) {
			throw new NotFoundException(__('Invalid page'));
		}
		$this->set('page', $this->Page->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			if (empty($this->request->data['Page']['date_start'])) {
				$this->request->data['Page']['date_start'] = date('Y-m-d H:i:s');
			} else {
				$this->request->data['Page']['date_start'] = date('Y-m-d H:i:s', strtotime($this->request->data['Page']['date_start']));
			}
			$this->request->data['Page']['slug'] = Inflector::slug(strtolower($this->request->data['Page']['title']), '-');
			$this->Page->create();
			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash(__('The page has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.'), 'Flash/admin/rror');
			}
		}
		if (empty($this->request->data['Page']['show_author'])){ $this->request->data['Page']['show_author'] = 0;}
		if (empty($this->request->data['Page']['show_created'])){ $this->request->data['Page']['show_created'] = 0;}
		if (empty($this->request->data['Page']['show_modified'])){ $this->request->data['Page']['show_modified'] = 0;}
		$templates = $this->findViews();
		$this->set(compact('templates'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Page->id = $id;
		if (!$this->Page->exists()) {
			throw new NotFoundException(__('Invalid page'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (empty($this->request->data['Page']['date_start'])) {
				$this->request->data['Page']['date_start'] = date('Y-m-d H:i:s');
			} else {
				$this->request->data['Page']['date_start'] = date('Y-m-d H:i:s', strtotime($this->request->data['Page']['date_start']));
			}
			$this->request->data['Page']['slug'] = Inflector::slug(strtolower($this->request->data['Page']['title']), '-');
			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash(__('The page has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->Page->read(null, $id);
		}
		$templates = $this->findViews();
		$this->set(compact('templates'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Page->id = $id;
		if (!$this->Page->exists()) {
			throw new NotFoundException(__('Invalid page'));
		}
		if ($this->Page->delete()) {
			$this->Session->setFlash(__('Page deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Page was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}

/**
 * admin_published method
 *
 * @param string $id
 * @return void
 */	
	function admin_published($id = null){
		$this->Page->id = $id;
		if (!$this->Page->exists()) {
			throw new NotFoundException(__('Invalid page'));
		}
		$this->layout = 'admin';
		$this->render(false);
		$this->Page->saveField('published', 0, false);
		$this->redirect(array('action' => 'index'));
			
	}

/**
 * admin_notpublished method
 *
 * @param string $id
 * @return void
 */
	function admin_notpublished($id){
		$this->Page->id = $id;
		if (!$this->Page->exists()) {
			throw new NotFoundException(__('Invalid page'));
		}
		$this->layout = 'admin';
		$this->render(false);
		$this->Page->saveField('published', 1, false);
		$this->redirect(array('action' => 'index'));
			
	}

/**
 * private findViews method
 *
 * @param string $folder
 * @return array
 */
	private function findViews( $folder = 'Pages' ){
		
		switch ($folder){
			default:
				App::uses('Sanitize', 'Utility');
				App::uses('Folder', 'Utility');
				$_path = (Configure::read('MySite.theme'))? 'Themed' . DS . Configure::read('MySite.theme') . DS . $folder: $folder;
				$_path = APP . DS . 'View' . DS . $_path;
				$_folder = new Folder($_path);
				$_ignore = array(
					'admin_index.ctp', 
					'admin_add.ctp',
					'admin_edit.ctp', 
					'admin_dashboard.ctp',
					'Errors',
					'Elements', 
					'Helpers',
					'Layouts',
					'Menu',
					'Menu_items',
					'Scaffolds',
					'.DS_Store');
				$_list = $_folder->tree($_path, $_ignore);
				//$_path = Sanitize::escape($_path);
				$_f = array();
				foreach($_list[1] as $f){
					$_name = str_replace(array('\\', '/', ".ctp"), '', str_replace($_path, '', $f));
					$_f[$_name] = $_name;
				}
				return $_f;
			break;
		}
		
	}
	
	private function slugger( $id, $title ) {
		App::uses('Inflector', 'Utility');
		return Inflector::slug($id.' '.$title, '-');
	}
	
}
