<?php

class contact extends AppModel {
	
	var $validate = array(
	    'name' => array(
	    	'rule' => array('notBlank'),
	        'message' => 'Please enter your name',
	        'allowEmpty' => false,
	        'required' => true,
	    ),
		'email' => array(
	        'rule' => 'email',
			'message' => 'Please enter a valid email address',
	        'required' => true,
	    ),
	);

	// This is where the magic happens
	function schema($field = false) {
		return array(
			'name' => array('type' => 'string', 'length' => 60),
			'email' => array('type' => 'string', 'length' => 60),
			'message' => array('type' => 'text', 'length' => 2000),
		);
	}
	
	
	public function strcompare( $str1, $str2 ) {
		$str1 = array_values($str1);
        $str1 = $str1[0];
        return strcasecmp($str1, $str2);
	}
	
}