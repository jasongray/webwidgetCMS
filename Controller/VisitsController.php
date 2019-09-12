<?php
App::uses('AppController', 'Controller');
/**
 * Visit Controller
 *
 */
class VisitsController extends AppController {
	
	public function beforeFilter() {
	    parent::beforeFilter();
		$this->Auth->allow('record'); 
	}
	
	public function record(){
		$this->autoRender = false;
		$this->Visit->add($this->Session->id());
		$this->render(false);
	}	
	
/**
 * admin_ajax
 * get report data in json format
 *
 * @return json
 */
	public function admin_ajax() {
		$this->response->header('Content-type', 'application/json;charset=utf-8');
		$this->layout = 'ajax';
		if($this->request->is('post')) {
			$this->set('data', $this->Visit->getVisitSummary());
		}
	}	
	
}