<?php
App::uses('AppController', 'Controller');
/**
 * Config Controller
 *
 */
class ConfigController extends AppController {
	
	function beforeFilter() {
	    parent::beforeFilter();
	}
	
/**
 * admin_index method
 *
 * @return void
 */
	function admin_index(){
		
		if ($this->request->is('post')) {
			if (isset($this->request->data['Image']['file']) && !empty($this->request->data['Image']['file'])) {
				unset($this->request->data['Config']['logo']);
				$file = $this->request->data['Image']['file'];
				if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
							
					$tempFile = $this->request->data['Image']['file']['tmp_name'];
					$targetPath = WWW_ROOT . 'img' . DS . 'logo';
				
					if(!is_dir($targetPath)){
						mkdir($targetPath, 0766);
					}
					$___fileinfo = pathinfo($this->request->data['Image']['file']['name']);
					$__data['file'] = $this->request->data['Image']['file']['name'];
					$targetFile =  str_replace('//','/',$targetPath) . '/' . $__data['file'];
					if(move_uploaded_file($tempFile,$targetFile)){
						$this->request->data['Config']['logo'] = $__data['file'];
					} 
				}
			}
			$this->saveToFile($this->request->data['Config']);
			Configure::load('mysite_config');
		} else {
			$this->request->data['Config'] = Configure::read('MySite');
		}
		
		$this->set('themes', $this->getThemes());
		
	}
	
/**
 * admin_cancel method
 *
 * @param string $id
 * @return void
 */
	function admin_cancel($id = null) {
		$this->Session->setFlash(__('Operation cancelled', true), 'Flash/admin/info');
		$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin', 'plugin' => false));
	}
	
	
	private function saveToFile( $data = false ){
		if($data){
			$file = APP . 'Config' . DS . 'mysite_config.php';
			if (is_writable($file)) {
				$handle = fopen($file, 'w');
				$txt = "<?php\n\n";
				foreach($data as $k => $v){
					$txt .= sprintf('$config[\'MySite\'][\'%s\'] = \'%s\'', $k, addslashes($v)).";\n";
				}
				$txt .= "
?>\n";
				if (fwrite($handle, $txt) === FALSE) {
					$this->Session->setFlash(__('Could not save config information to file.', true), 'Flash/admin/warning');
					return false;
				}
				fclose($handle);
				$this->Session->setFlash(__('Config information saved.', true), 'Flash/admin/success');
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin', 'plugin' => false));
			} else {
				$this->Session->setFlash(__('Config file is not writeable. Please correct the issue and try again!', true), 'Flash/admin/error');
				return false;
			}
		} else {
			$this->Session->setFlash(__('No data can be saved.', true), 'Flash/admin/error');
			return false;
		}
		
	}
	
	private function getThemes() {
		App::uses('Folder', 'Utility');
		$_path = APP . DS . 'View' . DS . 'Themed';
		$_folder = new Folder($_path);
		$_list = $_folder->read();
		if ($_list) {
			$_f = array();
			foreach($_list[0] as $f){
				$_f[$f] = $f;
			}
			return $_f;
		}
		return false;
	}

}
?>