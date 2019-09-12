<?php
App::uses('AppModel', 'Model');
/**
 * Customer Model
 *
 * @property User $User
 * @property Country $Country
 * @property Order $Order
 */
class Customer extends AppModel {

    public $primaryKey = 'cID';
 
    //The Associations below have been created with all possible keys, those that are not needed can be removed
 
/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

     
     
    public $validate = array(
        'cFirstName' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message'  => 'Please enter a valid firstname.'
        ),
        'cLastName' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message'  => 'Please enter a valid surname.'
        ),
        'cEmail' => array(
                'rule'     => 'email',
                'required' => true,
                'allowEmpty' => false,
                'message'  => 'Please enter a valid email address.'
        ),
        'cPassword' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Password should be at least 8 characters long'
            ),
            'size' => array(
                'rule' => array('between', 8, 20),
                'message' => 'Password should be at least 8 characters long'
            )
        ),
        'bAddress1' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message'  => 'Please enter your address.'
        ),
        'bLocality' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message'  => 'Please enter a valid suburb.'
        ),
        'cMobile' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message'  => 'Please include your phone number.'
        ),
    );
     
     
     
    public function getBalance($cid) {
         
        App::uses('OrderLog', 'Model');
        $this->OrderLog = new OrderLog();
        $this->OrderLog->virtualFields = array('balance' => 'SUM(OrderLog.amount)');
        return $this->OrderLog->find('first', array('fields' => 'balance', 'conditions' => array('Order.customer_id' => $cid, 'Order.order_status_id != 8')));
         
    }
     
    public function unvalidate() {
        $this->validator()->remove('bAddress1');
        $this->validator()->remove('bLocalityID');
        $this->validator()->remove('cMobile');
    }
     
    public function remvaliduserstuff() {
        $this->validator()->remove('cPassword');
    }
     
    public function remvaliduseremail() {
        $this->validator()->remove('cEmail');
    }
     
    public function addvalidation() {
        $this->validator()->add('cPassword', 'required', array(
            'rule' => 'alphaNumeric',
        ))->add('cPassword', 'size', array(
            'rule' => array('between', 8, 20),
            'message' => 'Password should be at least 8 characters long'
        ));
    }
     
    public function validateCreditCard() {
        $this->validator()->add(
            'paymentmethod', 'required', array(
                'rule' => 'notEmpty'
            )
        )->add(
            'cModile', 'required', array(
                'rule' => 'notEmpty'
            )
        );
    }
     
    public function addValidateCreditCard() {
        $this->validator()->add(
            'cardnumber', 'required', array(
                'rule' => 'cc',
                'message' => 'Please enter a valid card number.'
            )
        )->add(
            'exmonth', 'required', array(
                'rule' => array('minLength', 2),
                'message' => 'Please enter the month using two numbers.'
            )
        )->add(
            'exyear', 'required', array(
                'rule' => array('minLength', 2),
                'message' => 'Please enter the year using two numbers.'
            )
        )->add(
            'ccv', 'required', array(
                'rule' => 'numeric',
                'message' => 'Please enter your CCV number.'
            )
        )->add(
            'cardtype', 'required', array(
                'rule' => 'notEmpty'
            )
        );
    }

    public function getLocality($id) {
        $data = $this->query(sprintf('SELECT locality, state, postCode FROM locality WHERE suburbID = %s', $id));
        return $data[0]['locality'];
    }
 
}