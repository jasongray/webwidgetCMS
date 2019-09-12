<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * User Model
 *
 */
class User extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $actsAs = array('Acl' => array('type' => 'requester'));
	
	function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}
		if (isset($this->data['User']['role_id'])) {
			$role_id = $this->data['User']['role_id'];
		} else {
			$role_id = $this->field('role_id');
		}
		if (!$role_id) {
			return null;
		} else {
			return array('Role' => array('id' => $role_id));
		}
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['User']['password']) && !empty($this->data['User']['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data['User']['password'] = $passwordHasher->hash($this->data['User']['password']);
	    } else {
			unset($this->data['User']['password']);
		}
	    return true;

	}
	
	public function registerValidate() {
		$validate1 = array(
			'username' => array(
				'mustNotEmpty'=>array(
					'rule' => 'notEmpty',
					'message'=> 'Please enter a username',
					'last'=>true),
				'mustUnique'=>array(
					'rule' =>'isUnique',
					'message' =>'That username is already taken',)
			),
			'email'=> array(
				'mustNotEmpty'=>array(
					'rule' => 'notEmpty',
					'message'=> 'Please enter email',
					'last'=>true),
				'mustBeEmail'=> array(
					'rule' => array('email'),
					'message' => 'Please enter valid email',
					'last'=>true),
				'mustUnique'=>array(
					'rule' =>'isUnique',
					'message' =>'This email is already registered',)
			),
			'password'=>array(
				'mustNotEmpty'=>array(
					'rule' => 'notEmpty',
					'message'=> 'Please enter password',
					'on' => 'create',
					'last'=>true),
				'mustBeLonger'=>array(
					'rule' => array('minLength', 6),
					'message'=> 'Password must be greater than 5 characters',
					'on' => 'create',
					'last'=>true),
				'mustMatch'=>array(
					'rule' => array('verifies'),
					'message' => 'Both passwords must match'),
			)
		);
		
		$this->validate = $validate1;
		return $this->validates();
		
	}
	
	public function changePassword() {
		$validate1 = array(
			'password'=>array(
				'mustNotEmpty'=>array(
					'rule' => 'notEmpty',
					'message'=> 'Please enter your password',
					'on' => 'create',
					'last'=>true),
				'mustMatch' => array(
					'rule' => array('checkpassword'),
					'message'=> 'Old password is incorrect',
					'on' => 'update',
					'last'=>true),
			),	
			'newpassword'=>array(
				'mustNotEmpty'=>array(
					'rule' => 'notEmpty',
					'message'=> 'Please enter a new password',
					'on' => 'update',
					'last'=>true),
				'mustBeLonger'=>array(
					'rule' => array('minLength', 6),
					'message'=> 'Password must be greater than 5 characters',
					'on' => 'update',
					'last'=>true),
				'mustMatch'=>array(
					'rule' => array('newverifies'),
					'message' => 'Both passwords must match'),
			)
		);
		
		$this->validate = $validate1;
		return $this->validates();
		
	}
	
	public function sendUserMail($u = false, $tpl = 'default', $sbj = false) {
		if ($u) {
			$email = new CakeEmail('default');
			$email->config(array('from' => array(Configure::read('MySite.send_email') => Configure::read('MySite.send_from'))));
			$email->replyTo(Configure::read('MySite.send_email'));
			$email->returnPath(Configure::read('MySite.send_email'));
			$email->addHeaders(array('Organization' => Configure::read('MySite.site_name'), 'X-Priority' => 3));
			$email->helpers(array('Html'));
			$email->subject($sbj);
			$email->template($tpl);
			$email->emailFormat('both');
			$email->to($u['User']['email']);
			$email->domain('littlebricks.com.au');
			$theme = Configure::read('MySite.theme');
			if (!empty($theme)) {
				$email->theme($theme);
			}
			$email->viewVars(array(
				'sitename' => Configure::read('MySite.site_name'),
				'u' => $u
			));
			try{
				$result = $email->send();
			} catch (Exception $ex) {
				$result = __('Could not send email to user') . ' ' . $u['User']['id'];
			}
			$this->log($result, 'important', 'activity');
		}
	}
	
	public function verifies() {
		return ($this->data['User']['password'] === $this->data['User']['cpassword']);
	}
	
	public function newverifies() {
		return ($this->data['User']['newpassword'] === $this->data['User']['cpassword']);
	}
	
	public function checkpassword() {
		$this->id = $this->data['User']['id'];
		$_password = $this->field('password');
		if ($_password === AuthComponent::password($this->data['User']['password'])){
			return true;
		} else {
			return false;
		}
	}
	
	public function getQuickInfo() {
/*
		App::uses('News', 'Model');
		$this->News = new News;
		$this->News->recursive = -1;
		$this->News->virtualFields = array('ccnt' => 'COUNT(News.id)');
		$_c = $this->News->find('first', array('fields' => array('ccnt'), 'conditions' => array('News.published' => 1)));
		
		App::uses('Comment', 'Model');
		$this->Comment = new Comment;
		$this->Comment->recursive = -1;
		$this->Comment->virtualFields = array('pcnt' => 'COUNT(Comment.id)');
		$_p = $this->Comment->find('first', array('fields' => array('pcnt'), 'conditions' => array('Comment.status' => 1)));
*/		
		App::uses('Visit', 'Model');
		$this->Visit = new Visit;
		$this->Visit->virtualFields = array(
			'vcnt' => 'COUNT(Visit.id)',
			'ucnt' => 'COUNT(DISTINCT(Visit.ip))',
			'tcnt' => '(SELECT COUNT(Visit.id) FROM visits AS Visit WHERE DATE(Visit.created) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 1 DAY))',
			'tunt' => '(SELECT COUNT(DISTINCT(Visit.ip)) FROM visits AS Visit WHERE DATE(Visit.created) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 1 DAY))'
		);
		$_s = $this->Visit->find('first');
		
		return $_s;
		
	}
	
	public function getGraphInfo() {
		
		$sql = "SELECT y, m, COUNT(DISTINCT(`visits`.`session_id`)) AS count
				FROM (
					SELECT y, m
						FROM
							(SELECT YEAR(CURDATE()) y UNION ALL SELECT YEAR(CURDATE()) - 1) years,
							(SELECT 1 m UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12)
				months) ym
				LEFT JOIN visits
				ON ym.y = YEAR(`visits`.`created`)
				AND ym.m = MONTH(`visits`.`created`)
				WHERE
				(y = YEAR(CURDATE()) AND m <= MONTH(CURDATE()))
				OR
				(y < YEAR(CURDATE()) AND m > MONTH(CURDATE()))
				GROUP BY y, m;";
				
		return $this->query($sql);
		
	}
	
	public function getStaff() {
		$this->recursive = -1;
		return $this->find('all', array(
			'conditions' => array(
				'User.display' => 1,
				'User.active' => 1
			),
			'order' => array(
				'User.surname' => 'ASC'
			)
		));
	}

	public function identify($user = array()) {
		if (!empty($user)) {
			$_user = $this->find('first', array('conditions' => array('email' => $user['email'], 'password' => $user['password'])));
			if (!empty($_user)) {
				unset($_user['User']['password']);
				$_out['User'] = $_user['User'];
				unset($_user['User']);
				$_out['User'] = array_merge($_out['User'], $_user);
				return $_out;
			}
		}
		return false;
	}
	
/**
 * generatePassword method
 * 
 * Generates a random set of characters depending upon the parameters entered and saves to the user record.
 *
 * @param integer $uid The user id of the user to save the password
 * @param integer $length The number of characters to generate
 * @param integer $strength The strength of the password to generate. 1 being alpha only, 8 being alpha numeric with symbols.
 * @return string $password
 */		
	public function generatePassword($uid = false, $length = 10, $strength = 8) {
		
		$vowels = 'aeiou';
		$consonants = 'bcsfghjklmnpqrstvwxyz';
		if ($strength & 1) {
			$consonants .= 'BCDFGHJKLMNPQRSTVWXYZ';
		}
		if ($strength & 2) {
			$vowels .= "AEIOU";
		}
		if ($strength & 4) {
			$consonants .= '1234567890';
		}
		if ($strength & 8) {
			$consonants .= '@#$%!*_()^!?][{}|';
		}
		 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		if ($uid) {
			$this->id = $uid;
			$this->saveField('password', $password);
		}
		return $password;
		
	}

}
