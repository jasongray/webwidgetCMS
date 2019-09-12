<?php 

/**
 *	mod_tagcloud class
 *
 *
 */

class mod_tagcloud  {

	public function getTags($m) {
		$limit = array();
		if (isset($m['limit'])) {
			$limit = array('limit' => $m['limit']);
		}
		App::uses('Tag', 'Model');
		$this->Tag = new Tag;
		$this->Tag->recursive = -1;
		return $this->Tag->find('list', array_merge(array(
			'order' => 'Tag.count DESC',
			), 
			$limit)
		);
	}

}
?>