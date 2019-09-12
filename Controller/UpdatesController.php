<?php
/**
 *	Updates Constroller
 *
 *	Handles updating the databse for new products from master database
 *
 *	Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *
 *	Licensed under The MIT license
 *	Redistributions of this file must retain the above copyright notice
 *
 *	@copyright  	Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *	@package		WebCart
 *	@author			Jason Gray
 *	@version		1.8
 *	@license 		http://www.opensource.org/licenses/mit-license.php MIT License
 *
 */

App::uses('AppController', 'Controller');

/**
 * Update controller
 *
 * @package       WebCart
 */
class UpdatesController extends AppController {

/**
*	Reference to local temp directory for sql files to be saved
*
*	@var 	string
*/
	public $localFile;

	public $components = array('ProStore');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->localFile = APP . 'tmp' . DS . 'sqls' . DS . 'sqlnewproducts.sql';
		$this->backupFile = APP . 'tmp' . DS . 'sqls' . DS . 'sqlbackup.sql';
	}

/**
*	Updates products tables.
*
*	@return bool True on success, False with error message on failure
*/
	public function admin_index() {
		$this->autoRender = false;
		$key = $this->Session->id();
		$task = isset($this->params->named['task'])? $this->params->named['task']:'';
		$_productTable = Configure::read('MySite.remoteProductTable');

		switch ($task) {
			default:
				if ($sql = $this->ProStore->getRemoteFile($_productTable, $this->localFile, 'newproducts')) {
					$this->writeToFile($this->localFile, $sql);
					echo json_encode(array('session_id' => $key, 'duration' => '15', 'message' => __('Data from master database copied and remote file "'.$this->localFile.'" created')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Error connecting to master database')));
				}
			break;
			case 'savefile': 
				if ($result = $this->ProStore->getRemoteTable($_productTable, 'newproducts')) {
					$this->writeToFile($this->backupFile, $result);
					echo json_encode(array('session_id' => $key, 'duration' => '25', 'message' => __('Backup of remote table created... ready to create new local database table...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Failed to backup remote table')));
				}
			break;
			case 'create': case 'step1': case '1': case 'step 1':
				$this->Update->localSQL('DROP TABLE IF EXISTS `newproducts`');
				if ($this->Update->loadSqlFromFile($this->backupFile)) {
					echo json_encode(array('session_id' => $key, 'duration' => '35', 'message' => __('Table successfully created...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Failed to create table')));
				}
			break;
			case 'install': case 'step2': case '2': case 'step 2':
				if($this->Update->loadDataFromFile($this->localFile)) {
					echo json_encode(array('session_id' => $key, 'duration' => '45', 'message' => __('Query successfully imported and table created with data...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Failed to import data')));
				}
			break;
			case 'rename': case 'step3': case '3': case 'step 3':
				if ($this->Update->renameTables('products', 'newproducts')) {
					echo json_encode(array('session_id' => $key, 'duration' => '55', 'message' => __('New table created and loaded...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Failed to install new table')));
				}
			break;
			case 'upsql': case 'step4': case '4': case 'step 4':
				if ($this->Update->localSQL('DROP TABLE products_old')) {
					echo json_encode(array('session_id' => $key, 'duration' => '70', 'message' => __('Cleaning up old tables...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Could not clean up tables')));
				}
			break;
			case 'categories': case 'step5': case '5': case 'step 5':
				if ($this->Update->createCategories()) {
					echo json_encode(array('session_id' => $key, 'duration' => '85', 'message' => __('Creating new categories...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '0', 'message' => __('Could not create categories')));
				}
			break;
			case 'groups': case 'step6': case '6': case 'step 6':
				$this->Update->localSQL('TRUNCATE `groups`');
				if ($this->Update->createGroups()) {
					echo json_encode(array('session_id' => $key, 'duration' => '95', 'message' => __('Creating new groups...')));
				} else {
					echo json_encode(array('session_id' => $key, 'duration' => '95', 'message' => __('Could not create groups')));
				}
			break;
			case 'finish': case 'step7': case '7': case 'step 7':
				echo json_encode(array('session_id' => $key, 'duration' => '100', 'message' => __('Update complete, preparing to restart...')));
			break;
		}

	}

	public function admin_exportdata() {
		$_productTable = Configure::read('MySite.remoteProductTable');
		$result = $this->ProStore->getRemoteTable($_productTable, 'newproducts');
		if ($result) {
			echo '<p>Export of remote table created ready to create new local database table</p>';
			if ($this->Update->localSQL($result)) {
				echo '<p>Table successfully created</p>';
			}
		}
	}

	public function admin_loaddata() {
		if ($this->Update->loadDataFromFile($this->localFile)) {
			echo '<p>Query successfully imported and table created with data</p>';
		}
	}

	public function admin_loadproducts() {
		if ($this->Update->renameTables('products', 'newproducts')) {
			echo '<p>Data from new table successfully migrated to existing product table</p>';
		}
	}

	public function admin_generateCategories() {
		$this->Update->createCategories();
	}

	public function admin_generateGroups() {
		$this->Update->localSQL('TRUNCATE `groups`');
		$this->Update->createGroups();
	}


	private function writeToFile($file, $str) {
		$return = false;
		$h = fopen($file, 'w');
		if (fwrite($h, $str)) {
			$return = true;
		}
		fclose($h);
		return $return;
	}
	


}
