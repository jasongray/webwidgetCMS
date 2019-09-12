<?php
/**
 *	ContentRoutes Class
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
App::uses('Page', 'Model');

App::uses('CakeRoute', 'Routing/Route');
App::uses('ClassRegistry', 'Utility');

class ContentRoute extends CakeRoute {


	public function parse($url) {

		if(preg_match('/admin/', $url)) {
			return false;
		}
		$params = parent::parse($url);
        if (empty($params)) {
            return false;
        }

        $this->Page = ClassRegistry::init('Page'); 
        $page = $this->Page->find('first', array(
                    'conditions' => array(
                        'Page.slug' => $params['slug'],
                    ),
                    'fields' => array('Page.id', 'Page.slug'),
                    'recursive' => -1,
				));
        if ($page) {
            $params['pass'] = array($page['Page']['id'], $page['Page']['slug']);
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
		
		// Skip if not matching PagesController::display().
		if ($url['controller'] != 'pages' || $url['action'] != 'display') {
			return false;
		}
		
		$this->Page = ClassRegistry::init('Page'); 
		$_id = 0;		
		if (isset($url['id'])) {
			$_id = $url['id'];
			unset($url['id']);
		} else {
			// assumption is the mother of all fuckups
			if (isset($url[0]) && !empty($url[0])) {
				$_id = $url[0];
				unset($url[0]);
			}
		}
		$page = $this->Page->find('first', array(
				'conditions' => array(
					'Page.id' => $_id,
				),
				'fields' => array('Page.title', 'Page.slug'),
				'recursive' => -1,
			));
		if($page) {
			if (!empty($page['Page']['slug'])) {
				$url['slug'] = $page['Page']['slug'];
			} else {
				$url['slug'] = Inflector::slug(strtolower($page['Page']['title']), '-');
			}
		}
		return parent::match($url);
		
	}
	

}