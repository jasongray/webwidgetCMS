<?php 

/**
 *	mod_slideshow class
 *
 *
 */

class mod_slideshow  {

	public function reload($data) {
		return $this->getModuleParams($data['Module']['id'], false);
	}

	public function reorder($data) {
		if ($data) {
			$reorder = explode(',', $data['order']);
			foreach ($reorder as $i) {
				$params['images'][] = str_replace('file_', '', $i);
			}
			if (isset($params) && !empty($params['images'])) {
				$this->setModuleParams($data['Module']['id'], $params);
			}
		}
	}

	public function remove($data) {
		$params = $this->getModuleParams($data['Module']['id'], false);
		if (isset($params['images'])) {
			$folder = $data['folder'];
			$path = APP . WEBROOT_DIR . DS . 'img' . DS . $folder;
			$file = new File($path . DS . $params['images'][$data['index']]);
			if ($file->exists()){
				$file->delete();
			}
			unset($params['images'][$data['index']]);
			$this->setModuleParams($data['Module']['id'], $params);
			echo true; return;
		}
		echo false; return;
	}

	public function upload($data) {
		$error = '';
		$elm = $data['file_element'];
		if (!empty($_FILES[$elm]['error']) ) {
			switch ($_FILES[$elm]['error'] ) {
				case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
				case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
				case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
				case '4':
				$error = 'No file was uploaded.';
				break;
				case '6':
				$error = 'Missing a temporary folder';
				break;
				case '7':
				$error = 'Failed to write file to disk';
				break;
				case '8':
				$error = 'File upload stopped by extension';
				break;
				case '999':
				default:
				$error = 'No error code avaiable';
			}
		} else if (empty($_FILES[$elm]['tmp_name']) || $_FILES[$elm]['tmp_name'] == 'none') {
			$fnam = '';
			$path = '';
			$size = '';
			$error = 'File was not uploaded';
		} else {
			$fnam = $_FILES[$elm]['name'];
			$folder = $data['folder'];
			$size = @filesize($_FILES[$elm]['tmp_name']);
			$path = APP . WEBROOT_DIR . DS . 'img' . DS . $folder;
			$folder = new Folder($path);
			if (!file_exists($folder->path)) {
				$folder->create($path);
				$folder->chmod($path, 0777, true);
			}
			move_uploaded_file($_FILES[$elm]['tmp_name'], $path.DS.$fnam);
			$params = $this->getModuleParams($data['Module']['id'], false);
			if (is_array($params) && isset($params['images'])) {
				$params['images'] = array_merge_recursive($params['images'], array($fnam));
			} else if (is_array($params) && !isset($params['images'])) {
				$params['images'] = array($fnam);
			}
			if (!is_array($params)) {
				$params = array();
				$params['images'] = array($fnam);
			}
			$this->setModuleParams($data['Module']['id'], $params);
		}
		
		$res = new stdClass();
		$res->error = $error;
		$res->filename = $fnam;
		$res->path = $path;
		$res->size = sprintf("%.2fMB", $size/1048576);
		$res->dt = date('Y-m-d H:i:s');
		echo json_encode($res);	

	}

	public function save($data = false) {
		if ($data) {
			$params = $this->getModuleParams($data['Module']['id'], false);
			if (isset($params['images'])) {
				$data['Module']['params'] = array_merge($params, $data['params']);
				unset($data['params']);
			} 
			return $data;
		}
		return false;
	}

	private function getModuleParams($id = false, $decode = true) {
		App::uses('Module', 'Model');
		$this->Module = new Module;
		$result = $this->Module->read(null, $id);
		if ($result && !empty($result['Module']['params']) && $result['Module']['params'] != 'null') {
			if ($decode) {
				return json_decode($result['Module']['params'], true);
			} else {
				return $result['Module']['params'];
			}
		}
		return array();
	}

	private function setModuleParams($id = false, $data) {
		App::uses('Module', 'Model');
		$this->Module = new Module;
		$this->Module->id = $id;
		if ($this->Module->exists()) {
			return $this->Module->saveField('params', json_encode($data));
		}
		return false;
	}

}
?>