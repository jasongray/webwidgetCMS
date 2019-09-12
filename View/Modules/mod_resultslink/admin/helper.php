<?php 

/**
 *	mod_resultslink class
 *
 *
 */

class mod_resultslink  {

	public function getLink($link, $query = null) {
		App::uses('HttpSocket', 'Network/Http');
		$HttpSocket = new HttpSocket();
		$response = $HttpSocket->get($link, $query);
		if ($response->code === '200') {
			return $response->body;
		}
		return false;
	}

}
?>