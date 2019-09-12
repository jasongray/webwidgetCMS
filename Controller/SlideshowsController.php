<?php
App::uses('AppController', 'Controller');
/**
 * Slideshows Controller
 *
 * @property Slideshow $Slideshow
 */
class SlideshowsController extends AppController {

	function beforeFilter() {
    	parent::beforeFilter();
    	$this->Auth->allow(array('index'));
	}
	
	public function index() {
		$slides = $this->Slideshow->getSlides();
		if ($this->request->is('requested')) {
            return $slides; 
        } else {
            $this->set(compact('slides'));
        }
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Slideshow->recursive = 0;
		$this->paginate = array(
			'order' => array('Slideshow.lft' => 'ASC'),
			'limit' => 20
		);
		$this->set('slideshows', $this->paginate());
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
		$this->Slideshow->id = $id;
		if (!$this->Slideshow->exists()) {
			throw new NotFoundException(__('Invalid slideshow'));
		}
		$this->set('slideshow', $this->Slideshow->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Slideshow->create();
			if ($this->Slideshow->save($this->request->data)) {
				$this->Session->setFlash(__('The slideshow has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The slideshow could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		}
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Slideshow->id = $id;
		if (!$this->Slideshow->exists()) {
			throw new NotFoundException(__('Invalid slideshow'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Slideshow->save($this->request->data)) {
				$this->Session->setFlash(__('The slideshow has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The slideshow could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->Slideshow->read(null, $id);
		}
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Slideshow->id = $id;
		if (!$this->Slideshow->exists()) {
			throw new NotFoundException(__('Invalid slideshow'));
		}
		$this->removeImage($id);
		if ($this->Slideshow->delete()) {
			$this->Session->setFlash(__('Slideshow deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Slideshow was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}

	function admin_orderup($id = false){
		$this->autoRender = false;
		$this->Slideshow->id = $id;
		if($id){
			$this->Slideshow->moveUp($this->Slideshow->id, 1);
		}
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_orderdown($id = false){
		$this->autoRender = false;
		$this->Slideshow->id = $id;
		if($id){
			$this->Slideshow->moveDown($this->Slideshow->id, 1);
		}
		$this->redirect(array('action' => 'index'));
	}
	
	private function saveImage($id = false){
		
		$file = $this->data['Image']['file'];
		if ($file['error'] === UPLOAD_ERR_OK) {
					
			$tempFile = $this->data['Image']['file']['tmp_name'];
			$targetPath = WWW_ROOT . 'img/slideshows';
		
			if(!is_dir($targetPath)){
				mkdir($targetPath, 0766);
			}
			$___fileinfo = pathinfo($this->data['Image']['file']['name']);
			$__data['file'] = time() . md5($this->data['Image']['file']['name']) . '.' . strtolower($___fileinfo['extension']);
			$targetFile =  str_replace('//','/',$targetPath) . '/' . $__data['file'];
			if(move_uploaded_file($tempFile,$targetFile)){
				$this->Slideshow->saveField('image', $__data['file']);
			}else{
				$_result = '<p class="error">Failed to move uploaded file.</p>';
			}
		}
		
	}
	
	function admin_removeImage( $id = false ){
		$this->layout = 'ajax';
		$this->render(false);
		$this->Session->setFlash(__('Image was not removed', true), 'Flash/admin/error');
		if ($id) {
			$this->Slideshow->id = $id;
			$_img = $this->Slideshow->read('image', $id);
			if($_img && file_exists(WWW_ROOT . 'img/slideshows/' . $_img['Slideshow']['image'])){
				unlink(WWW_ROOT . 'img/slideshows/' . $_img['Slideshow']['image']);
			}
			if ($this->Slideshow->saveField('image', null)) {
				$this->Session->setFlash(__('Image was removed', true), 'Flash/admin/success');
			}
		}
		$this->redirect(array('action' => 'edit', $id));
		
	}
	
	private function removeImage($id) {
		$data = $this->Slideshow->read(null, $id);
		$file = new File(WWW_ROOT . 'img' . DS . 'slideshows'. DS . $data['Slideshow']['image']);
		$file->delete();
	}
}
