<?php
App::uses('AppModel', 'Model');
/**
 * Report Model
 *
 */
class Report extends AppModel {
	
	var $useTable = false;
	
	public function visitsByMonth() {
		
		$sql = "SELECT y, m, COUNT(DISTINCT(`visits`.`ip`)) AS count
				FROM (
					SELECT y, m
						FROM
							(SELECT YEAR(CURDATE()) y UNION ALL SELECT YEAR(CURDATE()) - 1) years,
							(SELECT 1 m UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12)
				months) ym
				LEFT JOIN `visits`
				ON ym.y = YEAR(`visits`.`created`)
				AND ym.m = MONTH(`visits`.`created`)
				WHERE
				(y = YEAR(CURDATE()) AND m <= MONTH(CURDATE()))
				OR
				(y < YEAR(CURDATE()) AND m > MONTH(CURDATE()))
				GROUP BY y, m;";
				
		return $this->query($sql);
		
	}
	
	public function visitsByDay($start = false, $end = false) {
		
		if (!$end) {
			$end = date('Y-m-d');
		}
		if (!$start) {
			$start = date('Y-m-d', strtotime(' -1 month'));
		}
		//$this->query("TRUNCATE calendar;");
		//$this->query("CALL fill_calendar('$start', '$end')");
		$sql = "SELECT UNIX_TIMESTAMP(caldate) AS day, (SELECT COUNT(DISTINCT(`visits`.`ip`)) FROM `visits` WHERE DATE(`visits`.`created`) = caldate) AS count
					FROM
					(
						SELECT curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as caldate
						FROM (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
						cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
						cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
					)  AS a

					WHERE caldate BETWEEN DATE('$start') AND DATE('$end')
					GROUP BY caldate
					ORDER BY caldate ASC";	
		return $this->query($sql);
		
	}
	
	public function visitsByPage($start = false, $end = false) {
		if (!$end) {
			$end = date('Y-m-d');
		}
		if (!$start) {
			$start = date('Y-m-d', strtotime(' -1 month'));
		}
		$sql = "SELECT COUNT(`visits`.`page`) AS `page_count`, `visits`.`page` 
				FROM `visits`
				WHERE DATE(`created`) BETWEEN DATE('$start') AND DATE('$end')
					AND `visits`.`page` NOT LIKE '%.jpg%' 
					AND `visits`.`page` NOT LIKE '%.png%' 
					AND `visits`.`page` NOT LIKE '%.gif%'
					AND `visits`.`page` NOT LIKE '%.jpeg%'
					AND `visits`.`page` NOT LIKE '%.css%'
					AND `visits`.`page` NOT LIKE '%.js%'
					AND `visits`.`page` NOT LIKE '%/img/%'
					AND `visits`.`page` NOT LIKE '%/css/%'
					AND `visits`.`page` NOT LIKE '%/js/%'
					AND `visits`.`page` NOT LIKE '%/file/%'
				GROUP BY page
				ORDER BY `page_count` DESC";
		return $this->query($sql);
	}
	
	public function getServerInfo() {
		$data = array();
		$data['systemload'] = $this->systemLoadInPercent();
		$data['diskfreespace'] = $this->getDiskFreeSpace();
		$data['totaldiskspace'] = $this->getDiskSpace();
		
		if(!$mem = $this->getMemory()) {
			$data['memoryuseage'] = $this->getMemoryUsed();
			$data['memorytotal'] = '2000000000';
		} else {
			$data['memoryuseage'] = abs($mem['MemTotal']) - abs($mem['MemFree']);
			$data['memorytotal'] = $mem['MemTotal'];
		}
		return $data;
	}
	
	private function systemLoadInPercent($interval = 1){
		if (function_exists('sys_getloadavg')) {
			$rs = sys_getloadavg();
			$interval = $interval >= 1 && 3 <= $interval ? $interval : 1;
		    $load  = $rs[$interval];
		    return round(($load * 100) / $this->getSystemCores(), 2);
		}
	    return 0;
	}
	
	private function getSystemCores(){
	    $cmd = "uname";
	    $OS = '';
	    if (is_callable('shell_exec') && false === stripos(ini_get('disable_functions'), 'shell_exec')){
	    	$OS = strtolower(trim(shell_exec($cmd)));
	    }

	    switch($OS){
	       case('linux'):
	          $cmd = "cat /proc/cpuinfo | grep processor | wc -l";
	          break;
	       case('freebsd'):
	          $cmd = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
	          break;
	       default:
	          unset($cmd);
	    }

	    if (isset($cmd) && $cmd != '' && is_callable('shell_exec') && false === stripos(ini_get('disable_functions'), 'shell_exec')){
	       $cpuCoreNo = intval(trim(shell_exec($cmd)));
	    }
	    return empty($cpuCoreNo) ? 4 : $cpuCoreNo;
	}	
	
	private function getDiskFreeSpace($dir = false){
		if(!$dir) {
			$dir = APP;
		}
		return disk_free_space($dir);
	}
	
	private function getDiskSpace($dir = false){
		if(!$dir) {
			$dir = APP;
		}
		return disk_total_space($dir);
	}
	
	private function getMemoryUsed() {
		return memory_get_usage();
	}
	
	private function getMemory() {
		$meminfo = array();
		if (file_exists("/proc/meminfo")) { 
			$data = explode("\n", file_get_contents("/proc/meminfo"));
			foreach ($data as $line) {
				if(!empty($line)){
					list($key, $val) = explode(":", $line);
					$meminfo[$key] = str_replace('kB','',trim($val));
				}
			}
		}
		if(!empty($meminfo)) {
			return $meminfo;
		} else {
			return false;
		}
	}
}