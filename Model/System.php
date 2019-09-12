<?php
App::uses('AppModel', 'Model');

class System extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = '';
	
/**
 * useTable field
 *
 * @var string
 */
	var $useTable = 'system';
	
/**
 * checkTables method
 * 
 * Returns the current version of this CMS.
 *
 * @return array $out An array of table data
 */	 
	public function checkTables() {

		// Check table status first
		$result = $this->query("SHOW TABLE STATUS");
		$out = array();
		foreach ($result as $r) {
			if ($r['TABLES']['Engine'] != 'InnoDB') {
				$t = $r['TABLES']['Name'];
				$_r = $this->query("CHECK TABLE $t");
				$out[] = array_merge($r['TABLES'], $_r[0][0]);
			}
		}
		return $out;
	}
	
	public function getVersion() {
		$data = $this->find('first', array('conditions' => array('result' => 1, 'version IS NOT NULL'), 'order' => array('id' => 'DESC')));
		if ($data) {
			return $data['System']['version'];
		} else {
			return '1.4.0'; // default version for this CMS
		}
	}

}