<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Galleries Controller
 *
 * @property Gallery $Gallery
 */
class GalleriesController extends AppController {
	
	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow(array('index', 'view'));
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Gallery->unbindModel(array('hasMany' => array('Galleryimage')));
		$this->Gallery->virtualFields = array('primaryfile' => 'Galleryimage.file', 'imgcount' => 'COUNT(Galleryimage.id)');
		$this->paginate = array(
			'limit' => 20, 
			'order' => 'Gallery.order ASC',
			'group' => 'Gallery.id',
			'joins' => array(
			array('table' => 'galleryimages',
				'alias' => 'Galleryimage',
				'type' => 'LEFT',
				'conditions' => array('Gallery.id = Galleryimage.gallery_id',
					)
				)
			),
			'conditions' => array('Gallery.published' => 1),
		);
		$this->set('gallery', $this->paginate());

		if ($this->request->is('ajax')) {
			$this->render('json-gallery');
		}
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null, $slug = null) {
		$this->Gallery->id = $id;
		if (!$this->Gallery->exists()) {
			throw new NotFoundException(__('Invalid gallery'));
		}
		$gallery = $this->Gallery->find('first', array('conditions' => array('Gallery.id' => $id)));
		$this->set('title_for_layout', $gallery['Gallery']['name']);
		$this->set(compact('gallery'));
		
		if ($this->request->is('ajax')) {
			$this->render('json-gallery');
		}

	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Gallery->unbindModel(array('hasMany' => array('Galleryimage')));
		$this->Gallery->virtualFields = array('primaryfile' => 'Galleryimage.file', 'imgcount' => 'COUNT(Galleryimage.id)');
		$this->paginate = array(
			'limit' => 20, 
			'order' => 'Gallery.order ASC',
			'group' => 'Gallery.id',
			'joins' => array(
			array('table' => 'galleryimages',
				'alias' => 'Galleryimage',
				'type' => 'LEFT',
				'conditions' => array('Gallery.id = Galleryimage.gallery_id',
					)
				)
			)
		);
		$this->set('gallery', $this->paginate());
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
		$this->Gallery->id = $id;
		if (!$this->Gallery->exists()) {
			throw new NotFoundException(__('Invalid gallery'));
		}
		$this->set('gallery', $this->Gallery->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Gallery->create();
			if ($this->Gallery->save($this->request->data)) {
				$this->Session->setFlash(__('The gallery has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'edit', $this->Gallery->id));
			} else {
				$this->Session->setFlash(__('The gallery could not be saved. Please, try again.'), 'Flash/admin/error');
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
		$this->Gallery->id = $id;
		if (!$this->Gallery->exists()) {
			throw new NotFoundException(__('Invalid gallery'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Gallery->save($this->request->data)) {
				$this->Session->setFlash(__('The gallery has been saved'), 'Flash/admin/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The gallery could not be saved. Please, try again.'), 'Flash/admin/error');
			}
		} else {
			$this->request->data = $this->Gallery->read(null, $id);
		}
	}

/**
 * admin_sort method
 *
 * @return void
 */
    public function admin_sort() {
    	if ($this->request->is('post')) {
    		$order = explode(",", $_POST['order']);
    		$i = 1;
    		foreach ($order as $photo) {
    			$this->Gallery->read(null, $photo);
    			$this->Gallery->set('order', $i);
    			$this->Gallery->save();
    			$i++;
            }
        }
        $this->render(false, false);
    }

/**
 * admin_sortimages method
 *
 * @return void
 */
    public function admin_sortimages() {
    	if ($this->request->is('post')) {
    		$order = explode(",", $_POST['order']);
    		$i = 1;
    		foreach ($order as $photo) {
    			$photo = str_replace('file_', '', $photo);
    			$this->Gallery->Galleryimage->read(null, $photo);
    			$this->Gallery->Galleryimage->set('order', $i);
    			$this->Gallery->Galleryimage->save();
    			$i++;
            }
        }
        $this->render(false, false);
    }

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Gallery->id = $id;
		if (!$this->Gallery->exists()) {
			throw new NotFoundException(__('Invalid gallery'));
		}
		if ($this->Gallery->deleteAll(array('Gallery.id' => $id))) {
			$data = $this->Gallery->Galleryimage->find('all', array('conditions' => array('gallery_id' => $id)));
			if ($data) {
				foreach ($data as $d) {
					$_location = APP . WEBROOT_DIR . DS . 'img' . DS . 'galleries' . DS . $d['Galleryimage']['gallery_id'] . DS . $d['Galleryimage']['file'];
					if (file_exists($_location) && !is_dir($_location)) {
						if (unlink($_location)) {
							$this->Gallery->Galleryimage->delete($d['Galleryimage']['id']);
						} 
					} 
				}	
			}
			$this->Session->setFlash(__('Gallery deleted'), 'Flash/admin/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Gallery was not deleted'), 'Flash/admin/error');
		$this->redirect(array('action' => 'index'));
	}

/**
 * upload method
 *
 * @param string $id
 * @return json string
 */	
	public function admin_upload($id = false) {
		$this->autoRender = false;
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Gallery->id = $id;
			$error = '';
			$elm = $this->request->data['file_element'];
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
				//$path = $this->request->data['folder'];
				$size = @filesize($_FILES[$elm]['tmp_name']);
				
				$path = APP . WEBROOT_DIR . DS . 'img' . DS . 'galleries' . DS . $this->Gallery->id;
				$folder = new Folder($path);
				if (!file_exists($folder->path)) {
					$folder->create($path);
					$folder->chmod($path, 0777, true);
				}
				move_uploaded_file($_FILES[$elm]['tmp_name'], $path.DS.$fnam);
				$this->Gallery->Galleryimage->save(array('file' => $fnam, 'gallery_id' => $this->Gallery->id));
				
			}
	
			$res = new stdClass();
			$res->error = $error;
			$res->filename = $fnam;
			$res->path = $path;
			$res->size = sprintf("%.2fMB", $size/1048576);
			$res->dt = date('Y-m-d H:i:s');
			echo json_encode($res);	
		}
	}

/**
 * admin_removeimage method
 *
 * @return boolean
 */
	public function admin_removeimage($id = false) {
		$this->autoRender = false;
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Gallery->Galleryimage->id = $id;
			$data = $this->Gallery->Galleryimage->read(null, $id);
			if ($data) {
				$_location = APP . WEBROOT_DIR . DS . 'img' . DS . 'galleries' . DS . $data['Galleryimage']['gallery_id'] . DS . $data['Galleryimage']['file'];
				if (file_exists($_location) && !is_dir($_location)) {
					if (unlink($_location)) {
						$this->Gallery->Galleryimage->delete($id);
						echo true;
					} 
				} 

			}
			
		}
		echo false;
	}
	
}
