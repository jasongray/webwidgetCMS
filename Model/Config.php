<?php

class Config extends AppModel {
	
	var $useTable = false;  // Not using the database, of course.


	public function fileSave($options = array()) {
		
		if (isset($this->data['Image']['file']) && !empty($this->data['Image']['file'])) {
			unset($this->data[$this->alias]['logo']);
			$file = $this->data['Image']['file'];
			if ($file['error'] === UPLOAD_ERR_OK) {
						
				$tempFile = $this->data['Image']['file']['tmp_name'];
				$targetPath = WWW_ROOT . 'img' . DS . 'logo';
			
				if(!is_dir($targetPath)){
					mkdir($targetPath, 0766);
				}
				$___fileinfo = pathinfo($this->data['Image']['file']['name']);
				$__data['file'] = $this->data['Image']['file']['name'];
				$targetFile =  str_replace('//','/',$targetPath) . '/' . $__data['file'];
				if(move_uploaded_file($tempFile,$targetFile)){
					$this->data[$this->alias]['logo'] = $__data['file'];
				} else {
					return false;
				}
			}
		}
		return true;
	}	

}