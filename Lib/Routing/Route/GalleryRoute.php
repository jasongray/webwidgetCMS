<?php
/**
 *	CategoryRoutes Class
 *
 *	Custom routing for this application
 *
 *	Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *
 *	Licensed under The MIT license
 *	Redistributions of this file must retain the above copyright notice
 *
 *	@copyright  	Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *	@package		WebCart
 *	@author			Jason Gray
 *	@version		1.8
 *	@license 		http://www.opensource.org/licenses/mit-license.php MIT License
 *
 */
App::uses('Gallery', 'Model');

App::uses('CakeRoute', 'Routing/Route');
App::uses('ClassRegistry', 'Utility');

class GalleryRoute extends CakeRoute {


	public function parse($url) {

		if(preg_match('/admin/', $url)) {
			return false;
		}
		$params = parent::parse($url);
        if (empty($params)) {
            return false;
        }

        $this->Gallery = ClassRegistry::init('Gallery'); 
        $page = $this->Gallery->find('first', array(
                    'conditions' => array(
                        'LOWER(Gallery.name)' => Inflector::slug($params['gallery'], ' '),
                    ),
                    'fields' => array('Gallery.id'),
                    'recursive' => -1,
				));
        if ($page) {
            $params['pass'] = array($page['Gallery']['id']);
            return $params;
        }
        return false;

	}
	
	public function match($url) {
		
		// Skip if admin
		if (isset($url['admin'])){
			$_prefixes = Configure::read('Routing.prefixes');
			foreach ($_prefixes as $admin) {
				if ($admin == $url['admin']) {
					return false;
				}
			}
		}
		
		// Skip if not matching CategoriesController::view().
		if ($url['controller'] != 'galleries' || $url['action'] != 'view') {
			return false;
		}
		
		$this->Gallery = ClassRegistry::init('Gallery'); 
		
		if (isset($url['id'])) {
			$_id = $url['id'];
			unset($url['id']);
		} else {
			// assumption is the mother of all fuckups
			$_id = $url[0];
			unset($url[0]);
		}
		if (isset($url['slug'])) {
			unset($url['slug']);
		}
		if (!isset($url['gallery'])) {
			$page = $this->Gallery->find('first', array(
					'conditions' => array(
						'Gallery.id' => $_id,
					),
					'fields' => array('Gallery.name'),
					'recursive' => -1,
				));
			if($page) {
				$url['gallery'] = Inflector::slug(strtolower($page['Gallery']['name']), '-');
			}
		}
		return parent::match($url);
		
	}
	

}