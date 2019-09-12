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
App::uses('Category', 'Model');

App::uses('CakeRoute', 'Routing/Route');
App::uses('ClassRegistry', 'Utility');

class CategoryRoute extends CakeRoute {


	public function parse($url) {

		if(preg_match('/admin/', $url)) {
			return false;
		}
		$params = parent::parse($url);
        if (empty($params)) {
            return false;
        }

        $this->Category = ClassRegistry::init('Category'); 
        $page = $this->Category->find('first', array(
                    'conditions' => array(
                        'LOWER(Category.title)' => Inflector::slug($params['category'], ' '),
                    ),
                    'fields' => array('Category.id'),
                    'recursive' => -1,
				));
        if ($page) {
            $params['pass'] = array($page['Category']['id']);
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
		if ($url['controller'] != 'categories' || $url['action'] != 'view') {
			return false;
		}
		
		$this->Category = ClassRegistry::init('Category'); 
		
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
		if (!isset($url['category'])) {
			$page = $this->Category->find('first', array(
					'conditions' => array(
						'Category.id' => $_id,
					),
					'fields' => array('Category.title'),
					'recursive' => -1,
				));
			if($page) {
				$url['category'] = Inflector::slug(strtolower($page['Category']['title']), '-');
			}
		}
		return parent::match($url);
		
	}
	

}