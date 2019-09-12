<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $helpers = array(
		'Html', 
		'Form', 
		'Session', 
		'Paginator', 
		'Img', 
		'Xhtml', 
		'Resize',
		'Menu', 
		'Module', 
		'Number', 
		'Time', 
		'Numtext', 
		'Cache',
		'Meta'
	);

	public $components = array(
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'passwordHasher' => 'Blowfish'
				)
			),
			'authorize' => array('Actions'),
			'flash' => array(
				'element' => 'Flash/admin/error',
				'key' => 'auth',
				'params' => array()
			),
		),
		'Acl',
		'Session',
		'Cookie',
		'RequestHandler',
	);

	public function beforeFilter() {

		parent::beforeFilter();
		$this->Cookie->name = 'WebwidgetCMS';
	    $this->Cookie->time = 7 * 24 * 60 * 60;    // 7 days
	    $this->Cookie->path = '/';
	    $this->Cookie->key = '}8<QyYcukm0P~qC32sjGTm*Z5LWg&&(t0#cK9Qkp<$!AnA==T5.)4x3Rh<E]';
	    $this->Cookie->httpOnly = true;
	    $this->Cookie->type('rijndael');

		$theme = Configure::read('MySite.theme');
		if (!empty($theme)) {
			$this->theme = $theme;
		}

		if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
			$this->Auth->loginAction = array(
				'controller' => 'users',
				'action' => 'login',
				'prefix' => 'admin',
				'plugin' => false
			);
			$this->Auth->logoutRedirect = array(
				'controller' => 'users',
				'action' => 'login',
				'prefix' => 'admin',
				'plugin' => false
			);
			$this->Auth->loginRedirect = array(
				'controller' => 'users',
				'prefix' => 'dashboard',
				'admin' => 'admin'
			);
			$this->layout = 'admin';
			$this->theme = '';
		}

		if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'members') {
			AuthComponent::$sessionKey = 'Auth.Member';
			$this->Auth->loginAction = array(
				'controller' => 'users',
				'action' => 'login',
				'prefix' => 'members',
				'plugin' => false
			);
			$this->Auth->logoutRedirect = array(
				'controller' => 'users',
				'action' => 'login',
				'prefix' => 'members',
				'plugin' => false
			);
			$this->Auth->loginRedirect = array(
				'controller' => 'users',
				'action' => 'dashboard',
				'prefix' => 'members'
			);
		}

		$this->paginate = array(
			'limit' => Configure::read('MySite.pagination.default'),
		);

		if (!$this->Auth->loggedIn()) {
			$this->Auth->authError = false;
		}

		$this->System = ClassRegistry::init('System');
		$this->set('version', $this->System->getVersion());
		$this->Auth->allow();  /* Global allow, comment out or delete for production. */
		//pr($this->params);
	}

	public function beforeRender() {

		if (isset($this->request->params['prefix']) && $this->request->params['prefix'] === 'admin' && $this->request->params['action'] != 'admin_login' && $this->layout != 'ajax' ) {

		} else {
			App::uses('MenuItem', 'Model');
			$this->_MenuItem = new MenuItem();
			$this->_MenuItem->recursive = -1;
			$pageVars = $this->_MenuItem->pageVars($this->request);
			if (!empty($pageVars['page_title'])) {
				$this->set('title_for_layout', $pageVars['page_title']);
			}
			if (!empty($pageVars['keywords'])) {
				$this->set('keywords_for_layout', $pageVars['keywords']);
			} else {
				$this->set('keywords_for_layout', '');
			}
			if (!empty($pageVars['description'])) {
				$this->set('description_for_layout', $pageVars['description']);
			} else {
				$this->set('description_for_layout', '');
			}
			if (!empty($pageVars['params'])) {
				$this->Session->write('pageparams', $pageVars['params']);
				$this->set('params', $pageVars['params']);
			} else {
				$params = $this->Session->read('pageparams');
				$params['id'] = 0;
				$this->set(compact('params'));
			}
			
		}
		
	}

/**
 * Send email function
 *
 * @param mixed $to String with email, Array with email as key, name as value or email as value (without name)
 * @param string $subject String subject of the email
 * @param string $template String template file name to use to send the email
 * @param array $data Array of variables to include in the email template
 * @return bool 
 */
	public function sendMail($to = false, $subject = false, $template = false, $data = array(), $attachments = false) {

		if (!empty($to) && !empty($subject) && !empty($template)) {
			$email = new CakeEmail('default');
			$email->config(array('from' => array(Configure::read('MySite.Mail.send_email') => Configure::read('MySite.Mail.send_from'))));
			$email->helpers(array('Html', 'Number', 'Img'));
			$email->subject($subject);
			$email->template($template);
			$email->emailFormat('both');
			$email->viewVars(compact('data'));
			$email->to($to);
			
			if (isset($this->theme) && !empty($this->theme)) {
				$email->theme($this->theme);
			} 

			if ($attachments) {
				$email->attachments($attachments);
			}

			return $email->send();
		}

		return false;

	}

	

}
