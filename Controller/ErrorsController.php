<?php
/**
 *	Errors Constroller
 *
 *	Displays error pages
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

App::uses('AppController', 'Controller');

/**
 * Error controller
 *
 * @package       WebCart
 */
class ErrorsController extends AppController {

	public $name = 'Errors';

/**
 * Displays 404 not found error page
 *
 * @return array $products Array of brands and most popular items
 *
 */
	public function error404() {
		$this->render('error400');
	}

}
