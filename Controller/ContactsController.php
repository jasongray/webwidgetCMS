<?php
App::uses('AppController', 'Controller');
/**
 * Contact Us Controller
 *
 */
class ContactsController extends AppController {
	
	function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('index');
	}
	
	function index(){
		App::uses('CakeEmail', 'Network/Email');
		if ($this->request->is('post')) {
			$this->Contact->set($this->request->data);
			if ($this->Contact->validates() && Configure::read('MySite.send_email')) {
				$email = new CakeEmail();
				$email->config('smtp');
				$email->to(array(Configure::read('MySite.send_email') => Configure::read('MySite.send_from')));   
				$email->subject($this->request->data['Contact']['subject']);
				$email->from(array($this->request->data['Contact']['email'] => $this->request->data['Contact']['name']));
				$_message = $this->request->data['Contact']['message'];
				$_message .= "\n\nFrom: " . $this->request->data['Contact']['name'];
				$_message .= "\nPhone: " . $this->request->data['Contact']['phone'];
				$_message .= "\nEmail: " . $this->request->data['Contact']['email'];
				$_message .= "\nIP Address: " . $_SERVER['REMOTE_ADDR'];
				$_message .= "\nDate: " . date('d-M-Y H:i');
				if(Configure::read('MySite.save_contact')) {
					$this->request->data['Contact']['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$this->Contact->save($this->request->data);
				}
				if($email->send($_message)){	
					unset($this->request->data);
					$this->Session->setFlash('Thanks for contacting us. Your message has been sent.', 'Flash/success', array(), 'contact');
				}else{
					$this->Session->setFlash('Unable to send the email at this time. ' . $email->smtpError, 'Flash/warning', array(), 'contact');
				}
			}else{
				$this->Session->setFlash('We cannot send your message at this time. ' . $this->validationErrors . '. Please try again.', 'Flash/error', array(), 'contact');
			}
		}
		$this->redirect($this->referer());
	}
	
}