<?php
/**
 *	App Exception Renderer
 *
 *	Display custom error pages
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

App::uses('ExceptionRenderer', 'Error');

/**
 * Error controller
 *
 * @package       WebCart
 */
class AppExceptionRenderer extends ExceptionRenderer {

/**
 * Custom render method for not found pages
 *
 * @return array $products Array of brands and most popular items
 *
 */
	public function notFound($error) {
		$this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));
	}

}
