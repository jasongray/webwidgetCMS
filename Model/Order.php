<?php
App::uses('AppModel', 'Model');
/**
 * Order Model
 *
 */
class Order extends AppModel {

    public $primaryKey = 'oID';
 
    //The Associations below have been created with all possible keys, those that are not needed can be removed
 
/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'Customer' => array(
            'className' => 'Customer',
            'foreignKey' => 'cID',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'OrderPriority' => array(
            'className' => 'OrderPriority',
            'foreignKey' => 'oID',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'OrderStatus' => array(
            'className' => 'OrderStatus',
            'foreignKey' => 'oID',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'OrderType' => array(
            'className' => 'OrderType',
            'foreignKey' => 'oID',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

/**
 * hasMany associations
 *
 * @var array
 */
    public $hasMany = array(
        'OrderItem' => array(
            'className' => 'OrderItem',
            'foreignKey' => 'oID',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

}