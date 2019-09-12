<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * System Controller
 *
 */
class SystemController extends AppController {
	
	var $components = array('Update');
	
	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow(array('feedback'));
	}


	public function feedback() {

		$this->set('title_for_layout', __('Leave Feedback'));

		if ($this->request->is('post') || $this->request->is('put')) {

			$this->request->data['Feedback']['project'] = 1;
			$this->request->data['Feedback']['assignee'] = 1;
			if (!empty($this->request->data['Feedback']['subject']) && !empty($this->request->data['Feedback']['description'])) {
				$return = $this->Update->sendfeedback($this->request->data);
				if (isset($return['message']) && isset($return['issue_id'])) {
					unset($this->request->data['Feedback']);
					$this->Session->setFlash(__('Thank you your feedback has been submitted'), 'Flash/info', array(), 'feedback');
				} else {
					$this->Session->setFlash($return, 'Flash/warning', array(), 'feedback');
				}
			} else {
				$this->Session->setFlash(__('Please include a subject/description'), 'Flash/error', array(), 'feedback');
			}

		}

	}


	public function admin_index() {
		$this->set('changelog', $this->Update->changelog());
		$this->set('tables', $this->System->checkTables());
		$this->set('update', $this->Update->check());
		$this->set('currentversion', $this->System->getVersion());
	}

	public function admin_update() {
		$this->autoRender = false;
		$key = $this->Session->id();
		$task = isset($this->params->named['task'])? $this->params->named['task']:'';
		$version = $this->Update->check();
		switch ($task) {
			default:
				if ($this->Update->check()) {
					echo json_encode(array('session_id' => $key, 'duration' => '10', 'message' => __(sprintf('Version %s available, creating backup...', $version))));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('No updates available')));
				}
			break;
			case 'backup': 
				if ($this->Update->backup()) {
					echo json_encode(array('session_id' => $key, 'duration' => '25', 'message' => __('Backup created... starting download...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Failed to backup site')));
				}
			break;
			case 'download': case 'step1': case '1': case 'step 1':
				if ($this->Update->download()) {
					echo json_encode(array('session_id' => $key, 'duration' => '35', 'message' => __('Package downloaded, preparing to extract...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Failed to download update package')));
				}
			break;
			case 'unzip': case 'step2': case '2': case 'step 2':
				if($this->Update->unzip()) {
					echo json_encode(array('session_id' => $key, 'duration' => '50', 'message' => __('Package extracted, preparing to install...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Failed to extract package')));
				}
			break;
			case 'install': case 'step3': case '3': case 'step 3':
				if ($this->Update->install()) {
					echo json_encode(array('session_id' => $key, 'duration' => '80', 'message' => __('Update installed, checking for database updates...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Failed to install files')));
				}
			break;
			case 'upsql': case 'step4': case '4': case 'step 4':
				if ($this->Update->sqlUpdates()) {
					echo json_encode(array('session_id' => $key, 'duration' => '95', 'message' => __('Database updates complete, finalising installation...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '95', 'message' => __('No database updates required')));
				}
			break;
			case 'finish': case 'step5': case '5': case 'step 5':
				$this->System->save(array('version' => $version, 'result' => 1));
				echo json_encode(array('session_id' => $key, 'duration' => '100', 'message' => __('Update complete, preparing to restart...')));
			break;
		}
	}

/**
 * optimiseTable method
 * 
 * Optimizes the specified table
 *
 * @return boolean
 */	 
	public function admin_optimiseTable() {
		$this->log(__('Optimising database table') . ' ' . $this->request->params['named']['table'], 'success', 'activity');
		$this->autoRender = false; // use this function in ajax requests.
		$table = $this->request->params['named']['table'];
		if ($table) {
			$_r = $this->System->query("OPTIMIZE TABLE $table");
			if ($_r[0][0]['Msg_text'] == 'OK') {
				$msg = array('response' => 1, 'msg' => __("Table \"$table\" was optimised"));
			} else {
				$msg = array('response' => 0, 'msg' => __("Table \"$table\" was not optimised"));
			}
		} else {
			$msg = array('response' => 0, 'msg' => __("Table \"$table\" not found"));
		}
		echo json_encode($msg);
	}

/**
 * optimiseAll method
 * 
 * Optimizes all tables in the database
 *
 * @return string
 */	 
	public function admin_optimiseAll() {
		$this->log(__('Optimising all database tables.'), 'success', 'activity');
		$this->autoRender = false; // use this function in ajax requests.
		// Check table status first
		$result = $this->System->query("SHOW TABLE STATUS");
		$out = array();
		foreach ($result as $r) {
			if ($r['TABLES']['Engine'] != 'InnoDB') {
				$t = $r['TABLES']['Name'];
				$_r = $this->System->query("OPTIMIZE TABLE $t");
				$out[] = array_merge($r['TABLES'], $_r[0][0]);
			}
		}
		echo json_encode($out);
	}

/**
 * emptyTable method
 * 
 * Truncates table in database
 *
 * @return string
 */	 
	public function admin_emptyTable() {
		$this->log(__('Truncating database table') . ' ' . $this->request->params['named']['table'], 'success', 'activity');
		$this->autoRender = false; // use this function in ajax requests.
		$table = $this->request->params['named']['table'];
		if ($table) {
			$_r = $this->System->query("TRUNCATE $table");
			if ($_r == 1) {
				$msg = array('response' => 1, 'msg' => __("Table \"$table\" has been emptied"));
			} else {
				$msg = array('response' => 0, 'msg' => __("Table \"$table\" was not emptied"));
			}
		} else {
			$msg = array('response' => 0, 'msg' => __("Table \"$table\" not found"));
		}
		echo json_encode($msg);
	}

	
}