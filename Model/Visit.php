<?php
App::uses('AppModel', 'Model');
/**
 * Visit Model
 *
 */
class Visit extends AppModel {
	
	public function add($session, $customer = '') {
		if (!$this->is_bot()) {
			$data['session_id'] = $session;
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$data['page'] = $_SERVER['REQUEST_URI'];
			$data['useragent'] = isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']: '';
			$data['keywords'] = $this->keywords();
			$this->save($data);
		}
	}
	
	public function is_bot() {
		$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot", "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot", "Butterfly","Twitturls","Me.dium","Twiceler");
		
		foreach($botlist as $bot) {
			if(strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) return true;
		}
		return false;
	}
	
	public function keywords($url = false) {
		
		if(!$url && !$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false) {
	        return '';
	    }
	
	    $parts_url = parse_url($url);
	    $query = isset($parts_url['query']) ? $parts_url['query'] : (isset($parts_url['fragment']) ? $parts_url['fragment'] : '');
	    if(!$query) {
	        return '';
	    }
	    parse_str($query, $parts_query);
	    return isset($parts_query['q']) ? $parts_query['q'] : (isset($parts_query['p']) ? $parts_query['p'] : '');

	}

	public function getVisitSummary(){
		$this->virtualFields = array(
			'vcnt' => 'COUNT(Visit.id)',
			'ucnt' => 'COUNT(DISTINCT(Visit.ip))',
			'tcnt' => '(SELECT COUNT(Visit.id) FROM visits AS Visit WHERE DATE(Visit.created) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 1 DAY))',
			'tunt' => '(SELECT COUNT(DISTINCT(Visit.ip)) FROM visits AS Visit WHERE DATE(Visit.created) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 1 DAY))'
		);
		return $this->find('first', array('fields' => array('vcnt', 'ucnt', 'tcnt', 'tunt')));
	}

}
