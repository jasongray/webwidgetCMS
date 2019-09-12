<?php
App::uses('CakeLogInterface', 'Log');

class ActivityLoggerLog implements CakeLogInterface {
	
    public function __construct($options = array()) {
        $_model = $options['model'];
        App::uses($_model, 'Model');
        $this->ActivityLog = new $_model;
    }

    public function write($type, $message) {
    	if (Configure::read('MySite.log') == 1) {
    		$auth_user_id = isset($_SESSION['Auth']['User']['id'])? $_SESSION['Auth']['User']['id']: '';
    		$auth_user_group_id = isset($_SESSION['Auth']['User']['role_id'])? $_SESSION['Auth']['User']['role_id']: '';
	        $this->ActivityLog->create();
	        $data = array(
	        	'ipaddr' => $_SERVER['REMOTE_ADDR'],
	        	'page' => $_SERVER['REQUEST_URI'],
	        	'user_id' => $auth_user_id,
	        	'group_id' => $auth_user_group_id,
		        'description' => $message,
		        'type' => $type
	        );
	        $this->ActivityLog->save($data);
	    }
    }
    
    
}