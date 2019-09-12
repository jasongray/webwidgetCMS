<?php
/**
 *	Meta Helper class file
 *
 *	Creates meta data tags
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

App::uses('AppHelper', 'View/Helper');

/**
*	MetaHelper class for creation of meta data tags
*
*	@package 		WebCart.Cake.View.Helper
*
*/
class MetaHelper extends AppHelper  {

/**
*	CakePHP helpers to load
*
*	@var 	array
*/
	public $helpers = array('Html', 'Xhtml', 'Img');

/**
*	Create social media meta tags
*
*	@param array
*	@return string
*/
	public function productpagemeta($data = array()) {

		$out = '';
		$out .= $this->Html->tag('html', null, array('itemscope', 'itemtype' => 'http://schema.org/Product', 'block' => 'meta'));
		$out .= $this->Html->meta('description', $this->Xhtml->trim($data['Product']['prdDescription'], 155), array('block' => 'meta'));
		$out .= $this->Html->meta('keywords', preg_replace('/\"|\'/', '', $data['Product']['prdKeyWords']), array('block' => 'meta'));

		// Add Google+ Meta tags
		$out .= $this->Html->meta(array('itemprop' => 'name', 'content' => $data['Product']['prdTitle']), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('itemprop' => 'description', 'content' => $this->Xhtml->trim($data['Product']['prdDescription'], 155)),null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('itemprop' => 'image', 'content' => $this->Html->url($this->Img->resize('products/'.$data['Product']['prdPrimaryImage'], 500, 500, true, array(), true, true), true)), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('itemprop' => 'price', 'content' => $data['Product']['prdSellPrice']), null, array('block' => 'meta'));

		// Add Twitter card meta tags
		$out .= $this->Html->meta(array('name' => 'twitter:card', 'content' => 'product'), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('name' => 'twitter:site', 'content' => Configure::read('MySite.name')), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('name' => 'twitter:title', 'content' => $data['Product']['prdTitle']), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('name' => 'twitter:description', 'content' => $this->Xhtml->trim($data['Product']['prdDescription'], 155)), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('name' => 'twitter:image', 'content' => $this->Html->url($this->Img->resize('products/'.$data['Product']['prdPrimaryImage'], 500, 500, true, array(), true, true), true)), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('name' => 'twitter:data1', 'content' => $data['Product']['prdSellPrice']), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('name' => 'twitter:label1', 'content' => 'Price'), null, array('block' => 'meta'));

		// Add Open Graph meta tags
		$out .= $this->Html->meta(array('property' => 'og:title', 'content' => $data['Product']['prdTitle']), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('property' => 'og:type', 'content' => 'product'), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('property' => 'og:url', 'content' => $this->Html->url($this->here, true)), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('property' => 'og:image', 'content' => $this->Html->url($this->Img->resize('products/'.$data['Product']['prdPrimaryImage'], 500, 500, true, array(), true, true), true)), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('property' => 'og:description', 'content' => $this->Xhtml->trim($data['Product']['prdDescription'], 155)), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('property' => 'og:site_name', 'content' => Configure::read('MySite.name')), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('property' => 'og:price:amount', 'content' => $data['Product']['prdSellPrice']), null, array('block' => 'meta'));
		$out .= $this->Html->meta(array('property' => 'og:price:currency', 'content' => 'AUD'), null, array('block' => 'meta'));

		return $out;									
	}

}
	