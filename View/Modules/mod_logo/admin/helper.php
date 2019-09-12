<?php 

/**
 *	mod_logo class
 *
 *
 */

class mod_logo {

/*
 * save function called from Module beforeSave used to manipulate the data array before saving to the database.
 *
 */
	public function save($data) {
		if (isset($data['Image']['file']) && !empty($data['Image']['file'])) {
			unset($data['params']['logo']);
			$file = $data['Image']['file'];
			if ($file['error'] === UPLOAD_ERR_OK) {
						
				$tempFile = $data['Image']['file']['tmp_name'];
				$targetPath = WWW_ROOT . 'img' . DS . 'logo';
			
				if(!is_dir($targetPath)){
					mkdir($targetPath, 0766);
				}
				$___fileinfo = pathinfo($data['Image']['file']['name']);
				$__data['file'] = $data['Image']['file']['name'];
				$targetFile =  str_replace('//', '/', $targetPath) . '/' . $__data['file'];
				if(move_uploaded_file($tempFile, $targetFile)){
					$data['params']['logo'] = $__data['file'];
				} else {
					return false;
				}
			}
		}
		return $data;
	}

}