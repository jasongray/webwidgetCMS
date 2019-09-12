<?php
/**
 *	ProductRoutes Class
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
App::uses('Product', 'Model');
App::uses('Brand', 'Model');
App::uses('Category', 'Model');
App::uses('Group', 'Model');

App::uses('CakeRoute', 'Routing/Route');
App::uses('ClassRegistry', 'Utility');

class ProductRoute extends CakeRoute {


	public function parse($url) {

		if(preg_match('/admin/', $url)) {
			return false;
		}
		
		$url = preg_replace('/\.html$/', '', $url);
		
		$params = array();
		$params = parent::parse($url);
        if (empty($params)) {
            return false;
        }

        $this->Group = ClassRegistry::init('Group'); 
        $this->Brand = ClassRegistry::init('Brand'); 
        $this->Category = ClassRegistry::init('Category'); 
		$_params = $_pass = array();

        if (isset($params['pass']) && !empty($params['pass'])) {

			for ($i=0; $i<count($params['pass']); $i++) {

				$str = $params['pass'][$i];

				if ($result = $this->Group->isGroup($str)) {
					$_pass[$i] = Inflector::slug(strtolower($result['Group']['title']), '-');
					$params['group'] = Inflector::slug(strtolower($result['Group']['title']), '-');
					$params['route']['group'] = $params['group'];
				} 
				if ($result = $this->Group->isSubGroup($str)) {
					$_pass[$i] = Inflector::slug(strtolower($result['Group']['title']), '-');
					$params['subgroup'] = Inflector::slug(strtolower($result['Group']['title']), '-');
					$params['route']['subgroup'] = $params['subgroup'];
				}

				if ($result = $this->Brand->isBrand($str)) {
					$_pass[$i] = Inflector::slug(strtolower($result['Brand']['prdLineName']), '-');
					$params['brand'] = Inflector::slug(strtolower($result['Brand']['prdLineName']), '-');
					$params['route']['brand'] = $params['brand'];
				}
				if ($result = $this->Brand->isCollection($str)) {
					$_pass[$i] = Inflector::slug(strtolower($result['Brand']['prdCollection']), '-');
					$params['collection'] = Inflector::slug(strtolower($result['Brand']['prdCollection']), '-');
					$params['route']['collection'] = $params['collection'];
				}

				if ($result = $this->Category->isCategory($str)) {
					$_pass[$i] = Inflector::slug(strtolower($result['Category']['title']), '-');
					$params['category'] = Inflector::slug(strtolower($result['Category']['title']), '-');
					$params['route']['category'] = $params['category'];
				}
				if ($result = $this->Category->isSubCategory($str)) {
					$_pass[$i] = Inflector::slug(strtolower($result['Category']['title']), '-');
					$params['subcategory'] = Inflector::slug(strtolower($result['Category']['title']), '-');
					$params['route']['subcategory'] = $params['subcategory'];
				}

			}

			$params['pass'] = $_pass;

		}

		return $params;

	}
	
	public function match($url) {
		
		$defaults = array('group', 'subgroup', 'category', 'subcategory', 'brand', 'collection');
		
		// Skip if admin
		if (isset($url['admin'])){
			$_prefixes = Configure::read('Routing.prefixes');
			foreach ($_prefixes as $admin) {
				if ($admin == $url['admin']) {
					return false;
				}
			}
		}
		
		// Skip if not matching ProductsController::index().
		if ($url['controller'] != 'products' || $url['action'] != 'index') {
			return false;
		}
		
		$out = '';
		$_defaults = $this->defaults;
		$named = $pass = array();
		$prefixes = Router::prefixes();
		
		foreach ($url as $key => $value) {
			
			$defaultExists = array_key_exists($key, $_defaults);
			if ($defaultExists && $_defaults[$key] != $value) {
				return false;
			} elseif ($defaultExists) {
				continue;
			}
			
			// pull out passed args
			$numeric = is_numeric($key);
			if ($numeric && isset($_defaults[$key]) && $_defaults[$key] == $value) {
				continue;
			} elseif ($numeric) {
				$pass[] = $value;
				unset($url[$key]);
				continue;
			}
			
			// pull out named params if named params are greedy or a rule exists.
			if (($value !== false && $value !== null) && (!in_array($key, $prefixes))) {
				if (in_array($key, $defaults)) {
					$pass[] = $value;
				} else {
					$named[$key] = $value;
				}
				continue;
			}
			
		}
		
		if (is_array($pass)) {
			$out = implode('/', array_map('rawurlencode', $pass));
		}
		if (!empty($named) && is_array($named)) {
			$_named = array();
			foreach ($named as $key => $value) {
				$_named[] = $key . ':' . rawurlencode($value);
			}
			$out = $out . '/' . implode('/', $_named);
		}
		
		return $out;
		
	}
	
	private static function is_assoc($a){
		$a = array_keys($a);
		return ($a != array_keys($a));
	}


}