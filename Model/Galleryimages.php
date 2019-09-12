<?php
App::uses('AppModel', 'Model');
/**
 * Galleryimages Model
 *
 * @property Menu $Gallery
 */
class Galleryimage extends AppModel {

	
	public $actsAs = array('Tree');

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Gallery' => array(
			'className' => 'Gallery',
			'foreignKey' => 'gallery_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
}
