<?php
/**
 *  Update Model.
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

App::uses('AppModel', 'Model');
App::uses('ConnectionManager', 'Model');

ini_set('max_execution_time', 300);

/**
 * Update model for WebCart.
 *
 * @package       WebCart
 */
class Update extends AppModel {

	public $useTable = false;

	public $dlm1;
	public $dlm2;

/**
 ***********************************************************************************
 *
 *	PRODUCT FUNCTIONS
 *	
 *
 *	Gather product information from remote site and import locally
 *
 ***********************************************************************************
 */

/**
*	Creates an sql file of the remote product table.
*
*	@param string $table name of remote database table.
*	@param string $file name of local file to save.
*	@param string $newtable name of the new local database table.
*	@return bool true of successful creation of the file, false on failure.
*/
	public function createRemoteFile($table = false, $file = false, $newtable = 'newtable') {

		if ($table && $file) {
			$this->setDataSource('remote');
			$dataSource = ConnectionManager::getDataSource('remote');
			$db = $dataSource->config['database'];
			$result = $this->query("SELECT * FROM `$table` AS NewData");
			if ($result) {
				$h = fopen($file, 'w');
				foreach ($result as $row) {
					$cols = count($row['NewData']);
					$sql = "INSERT INTO $newtable VALUES (";
					$j = 0;
					foreach ($row['NewData'] as $k => $v) {
						$v = addslashes($v);
						$v = ereg_replace("\n","\\n",$v);
						if (isset($v)) { $sql .= '"'.$v.'"' ; } else { $sql .= '""'; }
						if ($j<($cols-1)) { $sql .= ','; }
						$j++;
					}
					$sql .= ");\n\r";
					fwrite($h, $sql);
				}
				fclose($h);
				return true;
			}
		}

		return false;

	}

/**
*	Loads the sql file into the database.
*
*	@param string $file name of local file to load the data from.
*	@return bool true of successful creation of the file, false on failure.
*/
	public function loadDataFromFile($file = false) {

		if ($file) {
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$file = str_replace('\\', '/', $file);
			}
			$this->setDataSource('default');
			$sqls = file($file);
			foreach ($sqls as $sql) {
				if (!empty(trim($sql))) {
					$this->query($sql);
				}
			}
			return true;
		}
		return false;

	}

/**
*	Loads the sql file into the database.
*
*	@param string $file name of local file to load the data from.
*	@return bool true of successful creation of the file, false on failure.
*/
	public function loadSqlFromFile($file = false) {

		if ($file) {
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$file = str_replace('\\', '/', $file);
			}
			$this->setDataSource('default');
			$sqls = file_get_contents($file);
			if ($this->query($sqls)){
				return true;
			}
		}
		return false;

	}

/**
*	Creates a complete export of the remote table structure for copying to local server.
*
*	@param string $table name of remote database table.
*	@param string $newtable name of the new local database table.
*	@return bool returns the complete sql on success, false on failure.
*/
	public function exportRemoteTable($table = false, $newtable) {

		if ($table) {
			$this->setDataSource('remote');
			$dataSource = ConnectionManager::getDataSource('remote');
			$db = $dataSource->config['database'];
			if ($_columns = $this->query("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA` = '$db' AND `TABLE_NAME` = '$table' ORDER BY `ORDINAL_POSITION`;")) {
				$_primary = array();
				$_fulltext = array();
				$_table = $this->query("SELECT `TABLE_NAME`, `ENGINE`, `AUTO_INCREMENT`, `TABLE_COLLATION` FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_SCHEMA` = '$db' AND `TABLE_NAME` = '$table';");
				$sql = sprintf('CREATE TABLE IF NOT EXISTS `%1$s` ( '."\n", $newtable);
				foreach ($_columns as $c) {
					if (!empty($c['COLUMNS']['COLLATION_NAME'])) {
						$collation = explode('_', $c['COLUMNS']['COLLATION_NAME']);
						$charset = ' CHARACTER SET ' . $collation[0];
					} else {
						$charset = '';	
					} 
					if ($c['COLUMNS']['IS_NULLABLE'] == 'NO') {
						$nullable = ' NOT NULL';
					} else {
						switch ($c['COLUMNS']['DATA_TYPE']) {
							case 'longtext': $nullable = ''; break;
							case 'timestamp': $nullable = ' NULL';break;
							default: $nullable = ''; break;
						}
					}
					if (!empty($c['COLUMNS']['COLUMN_DEFAULT'])){
						$default = sprintf(" DEFAULT '%s'", $c['COLUMNS']['COLUMN_DEFAULT']);
					} else if ($c['COLUMNS']['IS_NULLABLE'] == 'NO'){
						$default = '';
					} else {
						$default = ' DEFAULT NULL';
					}
					$_sql[] = sprintf('`%1$s` %2$s%3$s%4$s%5$s', $c['COLUMNS']['COLUMN_NAME'], $c['COLUMNS']['COLUMN_TYPE'], $charset, $nullable, $default);
					if ($c['COLUMNS']['COLUMN_KEY'] == 'PRI') {
						$_primary[] = sprintf('`%s`', $c['COLUMNS']['COLUMN_NAME']);
					}
					if ($c['COLUMNS']['COLUMN_KEY'] == 'MUL') {
						$_fulltext[] = sprintf('`%s`', $c['COLUMNS']['COLUMN_NAME']);
					}
				}
				$sql .= implode(','."\n", $_sql) ."\n";
				$tablecollation = explode('_', $_table[0]['TABLES']['TABLE_COLLATION']);
				$sql .= sprintf(") ENGINE=%s AUTO_INCREMENT=%d DEFAULT CHARSET=%s;", $_table[0]['TABLES']['ENGINE'], $_table[0]['TABLES']['AUTO_INCREMENT'], $tablecollation[0]);
				if (!empty($_primary) || !empty($_fulltext)) {
					$sql .= "\n";
					$sql .= sprintf('ALTER TABLE `%s`'."\n", $newtable);
					$sql_add = array();
					if (!empty($_primary)) {
						$sql_add[] = sprintf(' ADD PRIMARY KEY (%s)', implode(',', $_primary));
					}
					if (!empty($_fulltext)) {
						foreach ($_fulltext as $f) {
							$sql_add[] = sprintf(' ADD FULLTEXT KEY %1$s (%1$s)', $f);
						}
					}
					$sql .= implode(',', $sql_add) .";";
				}

				$file = APP . 'tmp' . DS . 'sqls' . DS . 'sqlbackup.sql';
				$h = fopen($file, 'w');
				fwrite($h, $sql);
				fclose($h);

				return $sql;
			} 
		}
		return false;

	}


/**
*	Executes a query on the local databse from complete sql.
*
*	@param string $sql SQL to execute.
*	@return bool true on success, false on failure.
*/
	public function localSQL($sql = false, $return = false) {

		if ($sql && !empty($sql)) {
			$this->setDataSource('default');
			if ($result = $this->query($sql)) {
				if ($return) {
					return $result;
				} else {
					return true;
				}
			} 
		}
		return false;

	}

/**
*	Executes a query on the remote databse from complete sql.
*
*	@param string $sql SQL to execute.
*	@return bool true on success, false on failure.
*/
	public function remoteSQL($sql = false, $return = false) {

		if ($sql) {
			$this->setDataSource('remote');
			if ($result = $this->query($sql)) {
				if ($return) {
					return $result;
				} else {
					return true;
				}
			} 
		}
		return false;

	}


/**
*	Renames a given table to another name.
*
*	@param string $oldTableName the existing table to rename.
*	@param string $newTableName the new table name.
*	@return bool true on success, false on failure.
*/
	public function renameTables($oldTableName = false, $newTableName = false) {

		if ($oldTableName || $newTableName) {
			$this->setDataSource('default');
			$sql = "RENAME TABLE {$oldTableName} TO {$oldTableName}_old, $newTableName TO $oldTableName;";
			if ($this->query($sql)) {
				return true;
			} 
		}
		return false;

	}



/**
 ***********************************************************************************
 *
 *	CATEGORY FUNCTIONS
 *	
 *
 *	Split category columns from products table into categories table
 *
 ***********************************************************************************
 */

	
/**
*	Queries for categories from product table and sends array to be inserted into DB.
*
*	@return bool
*/
	public function createCategories() {
		$array = array();
		App::uses('Product', 'Model');
		$this->Product = new Product;
		$results = $this->Product->find('all', array('fields' => 'prdCategoryArray'));
		if ($results) {
			for ($i=0; $i<count($results); $i++ ) {
				$array[] = $results[$i]['Product']['prdCategoryArray'];
			}
			if ($this->saveCategoryArray($array)) {
				return true;
			}
		}
		return false;
	}

/**
*	Converts a delimited string of categories into a parent/child array.
*
*	@param string $str string of category names or id.
*	@param string $dlm1 Separator of Category lists.
*	@param string $dlm1 Separator of sub categories.
*	@return array Array of Main Category with children subcategories.
*/
	public function saveCategoryArray ($str = false, $dlm1 = ',', $dlm2 = '>') {

		$this->dlm1 = $dlm1;
		$this->dlm2 = $dlm2;

		if (is_array($str)) {
			$cats = array();
			foreach ($str as $s) {
				$cats = array_merge_recursive($cats, $this->strToArray($s));
			}
		} else {
			$cats = $this->strToArray($str);
		}
		$this->localSQL('TRUNCATE categories');
		$this->saveArray($cats);
		return true;
	}

/**
*	Private function to explode string into an assoicative array.
*
*	@param string $str string of category names or id.
*	@return array Array.
*/
	private function strToArray ($str = false) {

		$categories = array();
		$array = array();

		if ($str) {
			$_cats = array_map(
				function ($substr) {
					return explode($this->dlm2, $substr);
				}, 
				explode($this->dlm1, $str)
			);

			foreach ($_cats as $c) {	
				$array = array();
				foreach (array_reverse($c) as $arr) {
					$array = array($arr => $array);
				}
				$categories = array_merge_recursive($array, $categories);
			}
		
		}

		return $categories;

	}

	private function saveArray($array, $parent_id = null) {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		foreach ($array as $k => $v) {
			$this->Category->create();
			$this->Category->save(array('title' => $k, 'parent_id' => $parent_id));
			if (is_array($v) && !empty($v)) {
				$this->saveArray($v, $this->Category->id);
			} 
		}
	}



/**
 ***********************************************************************************
 *
 *	GROUP FUNCTIONS
 *	
 *
 *	Split group columns from products table into groups table
 *
 ***********************************************************************************
 */

/**
*	Generates array of group data to insert into DB.
*
*	@param string $str string of category names or id.
*	@return array Array.
*/
	public function createGroups() {
		$sql = "SELECT `prdGroupTitle` AS `title`, GROUP_CONCAT(DISTINCT `g1` ORDER BY prdGroupOrder) AS `children` FROM `products` AS `Group` WHERE 1 = 1 GROUP BY `prdGroupTitle` ORDER BY `prdGroupTitleOrder` ASC";
		if ($results = $this->localSQL($sql, true)) {
			//return $results;
			for ($i=0; $i<count($results); $i++) {
				$_array = explode(',', $results[$i][0]['children']);
				foreach ($_array as $s) {
					$results[$i]['Group']['children'][] = array('Group' => array('title' => $s));
				}
				unset( $results[$i][0]);
			}
			$this->saveGroup($results);
			return true;
		}
		return false;

	}

	private function saveGroup($array, $parent_id = null) {
		App::uses('Group', 'Model');
		$this->Group = new Group;
		foreach ($array as $c) {
			$this->Group->create();
			$this->Group->save(array('title' => $c['Group']['title'], 'parent_id' => $parent_id));
			if (isset($c['Group']['children']) && !empty($c['Group']['children'])) {
				$this->saveGroup($c['Group']['children'], $this->Group->id);
			} 
			
		}
	}
}
