<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	
	// ERROR ROUTE
	Router::connect('/404-not-found', array('controller' => 'errors', 'action' => 'error404'));
	
	// DEFAULT ROUTES
	Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
	Router::connect('/admin', array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin'));
	Router::connect('/members', array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'members'));

	// CONTENT ROUTE
	Router::connect('/:slug', array('controller' => 'pages', 'action' => 'display'), array('routeClass' => 'ContentRoute', 'pass' => array('slug')));

	Router::connect('/:category', array('controller' => 'categories', 'action' => 'view'), array('routeClass' => 'CategoryRoute', 'pass' => array('category')));
	
	Router::connect('/gallery/:gallery', array('controller' => 'galleries', 'action' => 'view'), array('routeClass' => 'GalleryRoute', 'pass' => array('gallery')));

	Router::connect('/tagged/:tag', array('controller' => 'news', 'action' => 'tagged'), array('pass' => array('tag')));

	Router::connect('/author/:author', array('controller' => 'news', 'action' => 'author'), array('pass' => array('author')));

	Router::connect('/:category/:slug', array('controller' => 'news', 'action' => 'view'), array('routeClass' => 'BlogRoute', 'pass' => array('slug', 'category')));

	// EVENT ROUTES

	Router::connect('/events/:year', array('controller' => 'events', 'action' => 'bydate'), array('routeClass' => 'EventsRoute', 'year' => '[12][0-9]{3}', 'pass' => array('year')));
	Router::connect('/events/:year/:month', array('controller' => 'events', 'action' => 'bydate'), array('routeClass' => 'EventsRoute', 'year' => '[12][0-9]{3}', 'month' => '0[1-9]|1[012]', 'pass' => array('year', 'month')));
	Router::connect('/events/:year/:month/:day', array('controller' => 'events', 'action' => 'bydate'), array('routeClass' => 'EventsRoute', 'year' => '[12][0-9]{3}', 'month' => '0[1-9]|1[012]', 'day' => '0[1-9]|[12][0-9]|3[01]', 'pass' => array('year', 'month', 'day')));
	Router::connect('/events/:category', array('controller' => 'events', 'action' => 'category'), array('routeClass' => 'EventsRoute', 'pass' => array('category')));
	Router::connect('/events/:category/:slug', array('controller' => 'events', 'action' => 'view'), array('routeClass' => 'EventsRoute', 'pass' => array('slug', 'category')));

	// SOME ADMIN ROUTES
	Router::connect('/admin/modules/functions/:id/:module/:function', array('controller' => 'modules', 'action' => 'functions', 'admin' => 'admin'), array('pass' => array('id', 'module', 'function')));
	Router::connect('/admin/activitylogs', array('controller' => 'activityLogs', 'action' => 'index', 'admin' => 'admin'));
	Router::connect('/admin/activitylogs/*', array('controller' => 'activityLogs', 'action' => 'index', 'admin' => 'admin'));


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
