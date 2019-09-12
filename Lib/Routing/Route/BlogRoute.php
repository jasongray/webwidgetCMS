<?php
/**
 *	BlogRoutes Class
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
App::uses('News', 'Model');

App::uses('CakeRoute', 'Routing/Route');
App::uses('ClassRegistry', 'Utility');

class BlogRoute extends CakeRoute {


	public function parse($url) {

		if(preg_match('/admin/', $url)) {
			return false;
		}
		$params = parent::parse($url);
        if (empty($params)) {
            return false;
        }

        $this->News = ClassRegistry::init('News'); 
        $page = $this->News->find('first', array(
                    'conditions' => array(
                        'News.slug' => $params['slug'],
                    ),
                    'fields' => array('News.id', 'News.slug'),
                    'recursive' => -1,
				));
        if ($page) {
            $params['pass'] = array($page['News']['id'], $page['News']['slug']);
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
		
		// Skip if not matching NewsController::view().
		if ($url['controller'] != 'news' || $url['action'] != 'view') {
			return false;
		}
		
		$this->News = ClassRegistry::init('News'); 
		
		if (isset($url['id'])) {
			$_id = $url['id'];
			unset($url['id']);
		} else {
			// assumption is the mother of all fuckups
			$_id = $url[0];
			unset($url[0]);
		}
		$page = $this->News->find('first', array(
				'conditions' => array(
					'News.id' => $_id,
				),
				'fields' => array('News.title', 'News.slug', 'Category.title'),
				'recursive' => 0,
			));
		if($page) {
			if (!empty($page['News']['slug'])) {
				$url['slug'] = $page['News']['slug'];
			} else {
				$url['slug'] = Inflector::slug(strtolower($page['News']['title']), '-');
			}
			if (!empty($page['Category']['title'])) {
				$url['category'] = Inflector::slug(strtolower($page['Category']['title']), '-');
			} else {
				$url['category'] = 'post';
			}
		}
		return parent::match($url);
		
	}
	

}