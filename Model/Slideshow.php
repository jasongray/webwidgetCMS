<?php
App::uses('AppModel', 'Model');
/**
 * Slideshow Model
 *
 */
class Slideshow extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	public $actsAs = array('Tree');
	
	public function getSlides() {
		$result = Cache::read('slideshow', 'longterm');
		if (!$result) {
			$this->recursive = 0;
			$result = $this->find('all', array('conditions' => array('Slideshow.published' => 1), 'order' => 'Slideshow.lft'));
			Cache::write('slideshow', $result, 'longterm');
		}
		return $result;
			
	}
	
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val[$this->alias]['params'])) {
				$results[$key][$this->alias]['params'] = json_decode($val[$this->alias]['params'], true);
			}
		}
		return $results;
	}
	
	
	public function beforeSave($options = array()) {
		if(empty($this->data[$this->alias]['id'])) {
			$_m = $this->find('first', array(
				'fields' => array(
					'MAX(Slideshow.ordering) as max_size'
				)
			));
			$this->data[$this->alias]['ordering'] = $_m[0]['max_size'] + 1;
		}
		if(isset($this->data[$this->alias]['params'])){
			$this->data[$this->alias]['params'] = json_encode($this->data[$this->alias]['params']);
		}
		
		if (isset($this->data['Image']['file']) && !empty($this->data['Image']['file'])) {
			unset($this->data[$this->alias]['image']);
			$file = $this->data['Image']['file'];
			if ($file['error'] === UPLOAD_ERR_OK) {
						
				$tempFile = $this->data['Image']['file']['tmp_name'];
				$targetPath = WWW_ROOT . 'img/slideshows';
			
				if(!is_dir($targetPath)){
					mkdir($targetPath, 0766);
				}
				$___fileinfo = pathinfo($this->data['Image']['file']['name']);
				$__data['file'] = time() . md5($this->data['Image']['file']['name']) . '.' . $___fileinfo['extension'];
				$targetFile =  str_replace('//','/',$targetPath) . '/' . $__data['file'];
				if(move_uploaded_file($tempFile,$targetFile)){
					$this->data[$this->alias]['image'] = $__data['file'];
				} else {
					return false;
				}
			}
		}
		return true;
	}
	
}
