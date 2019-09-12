<?php
/**
 *	EventRoutes Class
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
App::uses('Event', 'Model');

App::uses('CakeRoute', 'Routing/Route');
App::uses('ClassRegistry', 'Utility');

class EventsRoute extends CakeRoute {


	public function parse($url) {

		if(preg_match('/admin/', $url)) {
			return false;
		}
		$params = parent::parse($url);
        if (empty($params)) {
            return false;
        }

        if ($params['action'] == 'view') {
	        $this->Event = ClassRegistry::init('Event'); 
	        $page = $this->Event->find('first', array(
	                    'conditions' => array(
	                        'Event.slug' => $params['slug'],
	                    ),
	                    'fields' => array('Event.id', 'Event.slug'),
	                    'recursive' => -1,
					));
	        if ($page) {
	            $params['pass'] = array($page['Event']['id'], $page['Event']['slug']);
	            return $params;
	        }
	    }

	    if ($params['action'] == 'bydate') {
	    	if (isset($params['year'])) {
	    		$params['named'] = array_merge($params['named'], array('year' => $params['year']));
	    		unset($params['year']);
	    	}
	    	if (isset($params['month'])) {
	    		$params['named'] = array_merge($params['named'], array('month' => $params['month']));
	    		unset($params['month']);
	    	}
	    	if (isset($params['day'])) {
	    		$params['named'] = array_merge($params['named'], array('day' => $params['day']));
	    		unset($params['day']);
	    	}
	    	return $params;
	    }

	    if ($params['action'] == 'category') {
	    	App::uses('Category', 'Model');
	    	$this->Category = ClassRegistry::init('Category'); 
	        $page = $this->Category->find('first', array(
	                    'conditions' => array(
	                        'LOWER(Category.title)' => Inflector::slug($params['category'], ' '),
	                        'Category.type' => 4,
	                    ),
	                    'fields' => array('Category.id'),
	                    'recursive' => -1,
					));
	        if ($page) {
	            $params['pass'] = array($page['Category']['id']);
	            return $params;
	        }
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

		if ($url['controller'] == 'events' && $url['action'] == 'category') {
			App::uses('Category', 'Model');
			$this->Category = ClassRegistry::init('Category'); 
			$page = $this->Category->find('first', array(
				'conditions' => array(
					'Category.id' => $url['category'],
					'Category.type' => 4,
				),
				'fields' => array('Category.title'),
				'recursive' => -1,
			));
			if ($page) {
				$url['category'] = Inflector::slug(strtolower($page['Category']['title']), '-');
			} else {
				$url['category'] = __('uncategorised');
			}
			return parent::match($url);
		}
		
		// Skip if not matching NewsController::view().
		if ($url['controller'] != 'events' || $url['action'] != 'view') {
			return false;
		}

		$this->Event = ClassRegistry::init('Event'); 
		
		if (isset($url['id'])) {
			$_id = $url['id'];
			unset($url['id']);
		} else {
			// assumption is the mother of all fuckups
			$_id = $url[0];
			unset($url[0]);
		}
		$page = $this->Event->find('first', array(
				'conditions' => array(
					'Event.id' => $_id,
				),
				'fields' => array('Event.title', 'Event.slug', 'Category.title'),
				'recursive' => 0,
			));
		if($page) {
			if (!empty($page['Event']['slug'])) {
				$url['slug'] = $page['Event']['slug'];
			} else {
				$url['slug'] = Inflector::slug(strtolower($page['Event']['title']), '-');
			}
			if (!empty($page['Category']['title'])) {
				$url['category'] = Inflector::slug(strtolower($page['Category']['title']), '-');
			} else {
				$url['category'] = __('uncategorised');
			}
		}
		return parent::match($url);
		
	}
	

}