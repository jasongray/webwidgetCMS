<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Reports Controller
 *
 * @property Report $Report
 */
class ReportsController extends AppController {
	
	public function beforeFilter() {
	    parent::beforeFilter();
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$reports = array();
		$dir = new Folder(APP . 'View' . DS . 'Reports' . DS . 'Data');
		$files = $dir->find('.*\.ctp');
		if ($files) {
			$modules = array();
			foreach ($files as $file) {
				$reports[$file] = preg_replace('/\.ctp/', '', $file);
			}
		} 
		$this->set(compact('reports'));
	}
	
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_run($report = false) {
		if(!$report) {
			$this->Session->setFlash(__('Report not found'), 'Flash/admin/error');
			$this->redirect(array('action' => 'index'));
		}
		$start = (isset($this->params->named['startdate']))? $this->params->named['startdate']: false;
		$end = (isset($this->params->named['enddate']))? $this->params->named['enddate']: false;
		$func = Inflector::variable($report);
		$data = $this->Report->$func($start, $end);
		$this->set(compact('data'));
		$this->render('Data'.DS.$report);
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
			$this->set('data', $this->Report->visitsByMonth());
		}
	}
	
}