<?php
App::uses('AppModel', 'Model');
/**
 * Gallery Model
 *
 * @property Galleryimage $Galleryimage
 */
class Gallery extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $actsAs = array('Tree');

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Galleryimage' => array(
			'className' => 'Galleryimage',
			'foreignKey' => 'gallery_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'Galleryimage.order ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
