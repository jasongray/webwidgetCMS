<?php

App::uses('AppHelper', 'View');

/**
 * Xhtml helper
 *
 *
 */
class XhtmlHelper extends AppHelper {

	var $helpers = array('Html', 'Session', 'Number');

	public $bodyclass;

	function trim($str, $cnt){
		
		$_array = str_word_count(strip_tags($str), 2);
		$_str = array();
		if(count($_array) > 0){
			foreach($_array as $k => $v){
				if($k < $cnt){
					$_str[] = $v;
				}
			}
		}
		return implode(' ', $_str);
		
	}
	
	function navmenu($pclass, $navitem){
		if($pclass == $navitem){
			return ' mega-current';
		}
		return false;
	}

	function bodyClass() {
		if (!empty($this->bodyclass)) {
			if (is_array($this->bodyclass)) {
				return implode(' ', $this->bodyclass);
			} else {
				return $this->bodyclass;
			}
		}
		return '';
	}
	
	function mailto($email, $attributes = array()){
		$a = '';
		if(Validation::email($email)){
			$_email = preg_split('/(\@)/', $email);	
			$b = '';
			if (is_array($attributes) && count($attributes) > 0){
				foreach ($attributes as $key => $val){
					$b .= ' ' . $key . '="' . $val . '"';
				}
			}
			$a = '<script type="text/javascript">'.PHP_EOL;
			$a .= '<!-- '.PHP_EOL;
			$a .= " var m = ('".$_email[0]."&#64;' + '".$_email[1]."');".PHP_EOL;
			$a .= "document.write('<a href=\"mailto:' + m + '\"".$b.">' + m + '</a>');".PHP_EOL;
			$a .= '//-->'.PHP_EOL;
			$a .= '</script>'.PHP_EOL;
			$a .= '<ins><noscript>'.PHP_EOL;
			$a .= '<p><em>Email address protected by JavaScript.</em></p>'.PHP_EOL;
			$a .= '</noscript></ins>'.PHP_EOL;
		}
		return $a;
		
	}
	
	function legend($value=''){
		
		$legend = '<legend>';
		
		if(isset($value)){
			$legend .= $value;
		}
		
		$legend .= '</legend>';
		return $legend;
		
	}
	
	function fieldset($pos,$attributes=array()){
	
		$_lg = '';
		switch($pos){
			default:
			case 'start':
			$fset = '<fieldset ';
			if (is_array($attributes) && count($attributes) > 0){
				if(isset($attributes['legend']) && !empty($attributes['legend'])){
					$_lg = $this->legend($attributes['legend']);
					unset($attributes['legend']);
				}
				foreach ($attributes as $key => $val){
					$fset .= ' '.$key.'="'.$val.'"';
				}
			}
			$fset .= '>' . $_lg;
			break;
			case 'end':
			$fset = '</fieldset>';
			break;
		}			
			
		return $fset;
		
	}
	
	function getCrumbs($separator = '&raquo;', $startText = false) {
		
		if (!empty($this->Html->_crumbs)) {
			$rtn = '<ul>';
			$out = array();
			if ($startText) {
				$out[] = '<li>' . $this->Html->link($startText, '/') . '</li>';
			}

			foreach ($this->Html->_crumbs as $crumb) {
				if (!empty($crumb[1])) {
					$out[] = '<li>' . $this->Html->link($crumb[0], $crumb[1], $crumb[2]) . '</li>';
				} else {
					$out[] = '<li class="current">' . $crumb[0] . '</li>';
				}
			}
			$rtn .= join('<li>'.$separator.'</li>', $out) . '</ul>';
			return $rtn;
		} else {
			return null;
		}
		
	}
	
	function footer($string = null, $options = array()) {
		$str = '';
		if (preg_match('/\{SITE_NAME\}/', $string)) {
			$str = preg_replace('/\{SITE_NAME\}/', Configure::read('MySite.site_name'), $string);
		} else {
			if (isset($options['position']) && ($options['position'] == 'right' || $options['position'] == 'after')) {
				$str = Configure::read('MySite.site_name') . ' ' . $string;
			} else {
				$str = $string . ' ' . Configure::read('MySite.site_name');
			}
		}
		return $str;
	}
	
	function iconImage($type){
		
		switch ($type) {
			default: $ext = 'default'; break;
			case 'application/pdf': $ext = 'pdf'; break;
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'application/vnd.ms-excel': $ext = 'excel'; break;
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'application/msword': $ext = 'word'; break;
			case 'image/jpeg': 
			case 'image/jpg': $ext = 'jpg'; break;
			case 'image/png': $ext = 'png'; break;
			case 'image/gif': $ext = 'gif'; break;
		}
		return $ext;
	}
	
	function content($text = false){
		if($text){
			
			
		}
		return false;
	}
	
	
	function pagetitle($title_for_layout, $admin = false){
		$regexp = Configure::read('MySite.site_name_layout');
		if(empty($regexp)){
			$regexp = '{page title} :: {model} :: {site name}';
		}
		if (!$admin) {
			return preg_replace(
			array('/\{site name\}/i', '/\{model\}/i', '/\{page title\}/i'), 
			array(stripslashes(Configure::read('MySite.site_name')), Inflector::classify($this->request->params['controller']), $title_for_layout), 
			$regexp);
		} else {
			return preg_replace(
			array('/\{site name\}/i', '/\{model\}/i', '/\{page title\}/i'), 
			array(__('Webwidget CMS'), Inflector::classify($this->request->params['controller']), $title_for_layout), 
			$regexp);
		}
		
	}
	
	function randomColor(){
	    $randomcolor = '#' . strtoupper(dechex(rand(0,10000000)));
	    if (strlen($randomcolor) != 7){
	        $randomcolor = str_pad($randomcolor, 10, '0', STR_PAD_RIGHT);
	        $randomcolor = substr($randomcolor,0,7);
	    }
		return $randomcolor;
	}
	
	function visit(){
		App::uses('Visit', 'Model');
		$this->Visit = new Visit;
		$this->Visit->add($this->Session->read('Config.userAgent'), $this->Session->read('Customer'));
	}
	
	
	function googleAnalytics() {
		$ga = Configure::read('MySite.ga');
		if (!empty($ga)) {
			return "<script type=\"text/javascript\">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '$ga', 'auto');
  ga('send', 'pageview');</script>";
		}
	}
	
	
	function welcomemsg($username) {
		if (empty($username)) {
			$username = '';
		}
		$salute = __('Hi');
		$now = date('H');
		if ($now < '12') {
			$salute = __('Good morning');
		}
		if ($now < '18' && $now > '12') {
			$salute = __('Good afternoon');
		}
		if ($now < '00' && $now > '18') {
			$salute = __('Good evening');
		}
		return sprintf('<span>%s, %s</span>', $salute, $username);
	}
	
	
	function iconme($string = false) {
		$msg = 'icon-bell';
		if (strstr(strtolower($string), 'new')) {
			$msg = 'icon-plus';
		}
		if (strstr(strtolower($string), 'add')) {
			$msg = 'icon-plus';
		}
		if (strstr(strtolower($string), 'login')) {
			$msg = 'icon-bullhorn';
		}
		if (strstr(strtolower($string), 'logged in')) {
			$msg = 'icon-bullhorn';
		}
		if (strstr(strtolower($string), 'delete')) {
			$msg = 'icon-bolt';
		}
		if (strstr(strtolower($string), 'failed')) {
			$msg = 'icon-bolt';
		}
		if (strstr(strtolower($string), 'exception')) {
			$msg = 'icon-warning-sign';
		}
		if (strstr(strtolower($string), 'fatal')) {
			$msg = 'icon-ambulance';
		}
		if (strstr(strtolower($string), 'system')) {
			$msg = 'icon-cogs';
		}
		if (strstr(strtolower($string), 'database')) {
			$msg = 'icon-table';
		}
		return $msg;
	}
	
	function iconreplace($string = false) {
		$msg = 'info';
		if (strstr(strtolower($string), 'ok')) {
			$msg = 'success';
		}
		if (strstr(strtolower($string), 'critical')) {
			$msg = 'primary';
		}
		if (strstr(strtolower($string), 'fatal')) {
			$msg = 'inverse';
		}
		if (strstr(strtolower($string), 'error')) {
			$msg = 'danger';
		}
		return $msg;
	}
	
	public function rebatePrice ($price = null) {
		$rebate = $this->Session->read('Customer.rebate');
		/* 
		Rebates 
			0 = 0% 
			1 = 10%
			2 = 20%
			3 = 30%
		*/
		$rebates = $this->rebates();
		if ($price) {
			$lhc = $this->lhc($price);
			return $this->Number->currency($price - ($price * $rebates[$rebate]) + $lhc);
		}
		return false;
	}
	
	public function lhc($price = null) {
		$lhc_pcnt = '2';
		$magic_age = '30';
		$age = $this->Session->read('Customer.age');
		if ($age > $magic_age) {
			$lhc_age = $age - $magic_age;
			return $price * (($lhc_pcnt * $lhc_age) / 100);
		}
		return 0;
	}
	
	public function rebates() {
		return array(
			'3' => '0',
			'2' => '0.1',
			'1' => '0.2',
			'0' => '0.3'
		);
	}
	
	public function gov_rebate($rebate) {
		$rebates = $this->rebates();
		return $this->Number->toPercentage($rebates[$rebate], 0, array('multiply' => true));
	}
	
	public function lhc_percentage() {
		$lhc_pcnt = '2';
		$magic_age = '30';
		$age = $this->Session->read('Customer.age');
		if ($age > $magic_age) {
			$lhc_age = $age - $magic_age;
			return $this->Number->toPercentage($lhc_pcnt * $lhc_age, 0, array('multiply' => true));
		}
		return 0;
	}

	public function getVars() {
		return $this->_View->viewVars;
	}
	
	public function daysToGo($endDate = false) {
		if ($endDate) {
			$now = time();
			if (!is_numeric($endDate)) {
				$then = strtotime($endDate);
			} else {
				$then = $endDate;
			}
			$datediff = $then - $now;
			$days = floor($datediff/(60*60*24));
			$s = $this->pluralize($days, __('day'));
			
			if($days > 31) { 
				$days = floor($datediff/(60*60*24*365/12));
				$s = $this->pluralize($days, __('month'));
			}
			if($days > 12 && !preg_match('/day/', $s)) {
				$days = floor($datediff/(60*60*24*365));
				$s = $this->pluralize($days, __('year'));
			}
			return __('%s to go', $s);
		}
		return '';
	}
	
	function pluralize( $count, $text ){
	  return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
	}


	function colourmap($colour = '') {

		$colours = array(
			'#0066FF' => array(
					'blue',
					'cobalt blue',
					'blue/red',
					'blue/titanium',
					'blk/blue',
					'blue/black',
					'cobalt/black',
				),
			'#66A3FF' => array(
					'glacier/steel',
					'glacier',
					'glacier-steel',
					'grey/glacier',
					'glacier/grey',
				),
			'#30306A' => array(
					'midnight blue',
					'navy',
					'royal blue',
					
				),
			'#000000' => array(
					'black',
					'after dark',
					'black/multi',
					'black checks',
					'black Check',
					'batman theme',
					'black/silver',
					'black-titanium',
					'black/white',
					'black/titanium',
					'black/blue',
					'jet black',
				),
			'#FF9147' => array(
					'orange strip',
					'orange',
					'navy/orange',
					'navy.orange',
					'orange/pink',
					'ochre',
					'high vis orange',
					'mango',
					'grey/orange',

				),
			'#33D633' => array(
					'grey/lime',
					'lime green',
					'lime',
					'mint green',
					'cactus',
					'cactus/lime',
					'high vis green',
					'green/grey',
					'bright green',
					'cactus/titanium',
				),
			'#29AB29' => array(
					'black/green',
					'blue/green',
					'blue-green',
					'mint',
					'bottle green',
					'forest',
					'green',
					'pine green',
					'forest charcoal',
					'fern/black',
				),
			'#63633E' => array(
					'khaki',
					'khaki green',
					'khaki mesh',
					'olive',
					'camo',
					'lime/cactus',
					'lime-cactus',
					'camo army',
				),
			'#D11919' => array(
					'red-blue',
					'red',
					'chilli',
					'chilli/white',
					'chilli/black',
					'blk/red',
					'red/black',
				),
			'#FF1975' => array(
					'fuschia',
					'magenta',
					'hot pink',
					'pink',
					'pink/navy',
					'magenta charcoal',
					'pink bears',
				),
			'#751947' => array(
					'purple/blue',
					'purple',
					'aubergine',
				),
			'#C2C2D6' => array(
					'grey/purple',
					'grey',
					'grey/citrus',
					'graphite',
					'stone',
					'steel/glacier',
					'steel/white/glacier',
					'steel',
					'silver',
					'navy-steel',
					
				),
			'#3A3A40' => array(
					'charcoal',
					'charcoal grey',
					'titanium',
					'charcoal/black',
				),
			'#8A2E00' => array(
					'espresso brown',
					'brick',
					'cha',
				),
			'#00CC7A' => array(
					'turquoise',
					'teal',
				),
			'#E6B800' => array(
					'sunflower',
					'yellow',
					'yellow/pink',	
					'navy/yellow',
					'mustard/black',
				),
			'#85A3E0' => array(
					'seaside stripe',
					'light blue',
					'navy/steel',
					'blue titanium',
					'pacific',
					'blue ocean',
				)

			
		);

		$_c = $this->find_keys_based_on_value(strtolower($colour), $colours);
		if (empty($_c)) {
			return '#FFFFFF';
		} else {
			return $_c;
		}

	}


	private function find_keys_based_on_value($value, $array, $first_only = true, $key_names_only = true) {
		$results = array();
		foreach($array as $parent_key_name => $parent_key_values) {
			if(is_array($parent_key_values)) {
				foreach($parent_key_values as $key_value) {
					if($key_value==$value) {
						if($first_only)
						return $key_names_only ? $parent_key_name : $parent_key_values; // return first key matched; exit function
					else
						$results[] = $key_names_only ? $parent_key_name : $parent_key_values; // keep result for output later
					}
				}
			}
		}
		return $results; // function will have stopped execution if $first_only = true, so it must be false
	}


/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
	function get_gravatar($email, $img = false, $atts = array(), $s = 80, $d = 'mm', $r = 'g') {
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ($img) {
			$url = $this->Html->image($url, $atts);
		}
		return $url;
	}

}