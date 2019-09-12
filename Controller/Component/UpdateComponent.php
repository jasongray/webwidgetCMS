<?php
App::uses('Component', 'Controller');
App::uses('MyFolder', 'Utility');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class UpdateComponent extends Component  {
	
	public $updateurl = null;
	public $savepath = null;
	public $savefile = null;
	public $latestupdate = null;
	public $latestversion = null;
	protected $_controller = null;
	private $api_key;
	
	public function initialize(Controller $controller) {
		$this->_controller = $controller;
		$this->updateurl = 'http://webwidget.com.au/_webwidgetcms/updates/';
		$this->savepath = APP . 'tmp' . DS . 'updates' . DS;
	}
	
	public function check($ver = 0) {
		if ($ver == 0) {
			$ver = $this->currentVersion();
		}
		$updatefile = $this->updateurl . 'updates.ini';
		$update = $this->curl($updatefile);
		
		if ($update == false) {
			$this->log(__('Could not download ini file ').$updatefile, 'important', 'activity');
			return false;
		} else {
			$versions = $this->parse_inistring($update);
			if (is_array($versions)) {
				$versions = array_reverse($versions);
				foreach ($versions as $key => $val) {
					if (str_replace('.', '', $val['version']) > str_replace('.', '', $ver)) {
						$this->latestversion = $val['version'];
						$this->latestupdate = $val['url'];
						return $this->latestversion;
					}
				}
			} else {
				$this->log(__('Unable to read and parse ini file'), 'important', 'activity');
				return false;
			}
		}
		
	}

	public function changelog($ver = 0) {
		if ($ver == 0) {
			$ver = $this->currentVersion();
		}
		$updatefile = $this->updateurl . 'cms.changelog';
		$update = $this->curl($updatefile);
		
		if ($update) {
			return $update;
		} 
		return false;
		
	}
	
	public function backup($folder = false) {
		if (!$folder) {
			$folder = APP;
		}
		
		$storedir = APP.'tmp'.DS.'backups';
		$dir = new Folder($storedir, true, 0777);
		$archiveName = date('Ymd').time().'.zip';
		ini_set('max_execution_time', 300);
		require_once 'zip/pclzip.lib.php';
		$zip = new PclZip($storedir.DS.$archiveName);
		$exclude = array('tmp', 'backups', 'cache', 'imgs', 'logs', 'sessions', 'sqls', 'updates', 'error.log', 'debug.log');
		$xfile = new Folder($storedir);
		$exfiles = $xfile->read();
		$exclude = array_merge($exclude, $exfiles[1]);
		$files = new Folder($folder);
		$_files = $files->tree(null, $exclude, 'file');
		if ($zip->create($_files, '', $folder)) {
			$this->log(__(sprintf('Backup file created "%s"', $archiveName)), 'info', 'activity');
			return true;
		} else {
			$this->log(__('Backup unsuccessful ').$zip->errorInfo(true), 'error', 'activity');
			return false;
		}
	}
	
	public function download() {
		$this->check();
		if (isset($this->latestupdate) && !empty($this->latestupdate)) {
			$this->savefile = $this->savepath . $this->latestversion . '.zip';
			$file = new File($this->savefile);
			if (!$file->exists()) {
				$update = $this->curl($this->latestupdate);
				$handle = fopen($this->savefile, 'w');
				if (!$handle) {
					$this->log(__('Could not save update file'), 'error', 'activity');
					return false;
				}
				if (!fwrite($handle, $update)) {
					$this->log(__('Could not write to update file'), 'error', 'activity');
					return false;
				}
				fclose($handle);
			} else {
				$file->delete();
				$this->download();
			}
			return true;
		} else {
			$this->log(__('Error ' . $this->latestversion), 'error', 'activity');
		}
		return false;
	}
	
	public function install() {
		$this->check();
		$__foldername = $this->savepath . $this->latestversion;
		$folder = new Folder($__foldername);
		if (@$folder->copy(array(
				'from' => $__foldername,
				'to' => substr(APP, 0, -1),
				'chmod' => 0755,
				'skip' => array('.DS_STORE', '.DS_Store', '__MACOSX', 'sql'),
				'scheme' => Folder::MERGE
			))) {
			$this->log(__(sprintf('Version %s files copied', $this->latestversion)), 'info', 'activity');
			return true;
		} else {
			$this->log(__(sprintf('Unable to copy %s', implode(', ', $folder->errors()))), 'error', 'activity');
			return false;
		}
	}
	
	public function unzip() {
		$this->check();
		$this->savefile = $this->savepath .  $this->latestversion . '.zip';
		if (!empty($this->savefile)) {
			$__foldername = $this->savepath . $this->latestversion;
			if (file_exists($__foldername)) {
				$folder = new Folder($__foldername);
				$folder->delete();
			}
			if(!is_dir($__foldername)){
				$folder = new Folder();
				$folder->create($__foldername);
				$folder->chmod($__foldername, 0777, true);
			}
			require_once 'zip/pclzip.lib.php';
			$zip = new PclZip($this->savefile);
			if ($zip->extract(PCLZIP_OPT_PATH, $__foldername) == 0) {
				$this->log(sprintf(__('Error extracting zip file %s'), $this->savefile).$zip->errorInfo(true), 'error', 'activity');
				return false;
			} else {
				return true;
			}
		}
	}
	
	public function sqlUpdates() {
		$this->check();
		$__foldername = $this->savepath . $this->latestversion . DS . 'sql';
		$folder = new Folder($__foldername);
		$files = $folder->find('.*\.sql');
		if ($files) {
			foreach ($files as $file) {
				$_file = new File($__foldername.DS.$file);
				if ($_file->exists()) {
					$_contents = $_file->read();
					if ($this->saveQuery($_contents)) {
						return true;
					}
				}
			}
		}
		return false;
	}
	
	public function currentVersion() {
		$this->System = ClassRegistry::init('System');
		return $this->System->getVersion();
	}
	
	private function saveQuery($q = false) {
		$this->System = ClassRegistry::init('System');
		return $this->System->query($q);
	}

	private function parse_inistring($str) {

	    if(empty($str)) return false;

	    $lines = explode("\n", $str);
	    $ret = array();
	    $inside_section = false;

	    foreach($lines as $line) {

	        $line = trim($line);

	        if(!$line || $line[0] == "#" || $line[0] == ";") continue;

	        if($line[0] == "[" && $endIdx = strpos($line, "]")){
	            $inside_section = substr($line, 1, $endIdx-1);
	            continue;
	        }

	        if(!strpos($line, '=')) continue;

	        $tmp = explode("=", $line, 2);

	        if($inside_section) {

	            $key = rtrim($tmp[0]);
	            $value = ltrim($tmp[1]);

	            if(preg_match("/^\".*\"$/", $value) || preg_match("/^'.*'$/", $value)) {
	                $value = mb_substr($value, 1, mb_strlen($value) - 2);
	            }

	            $t = preg_match("^\[(.*?)\]^", $key, $matches);
	            if(!empty($matches) && isset($matches[0])) {

	                $arr_name = preg_replace('#\[(.*?)\]#is', '', $key);

	                if(!isset($ret[$inside_section][$arr_name]) || !is_array($ret[$inside_section][$arr_name])) {
	                    $ret[$inside_section][$arr_name] = array();
	                }

	                if(isset($matches[1]) && !empty($matches[1])) {
	                    $ret[$inside_section][$arr_name][$matches[1]] = $value;
	                } else {
	                    $ret[$inside_section][$arr_name][] = $value;
	                }

	            } else {
	                $ret[$inside_section][trim($tmp[0])] = $value;
	            }           

	        } else {

	            $ret[trim($tmp[0])] = ltrim($tmp[1]);

	        }
	    }
	    return $ret;
	}

	public function sendfeedback($data) {
		$this->api_key = '612e634e5be4f3cfd8428da87fa33b48';
		return $this->_postcurl($data['Feedback']);
	}

/**
 * POSTS url via CURL extension and retrieves response
 *
 * @param array $data Array of data to send via POST
 * @return array The response
 */
    private function _postcurl($data = array()) {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://bugs.webwidget.com.au/api/issues.json',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_USERPWD => $this->api_key . ': ',
            )
        );

        $contents = curl_exec ($ch);
        curl_close ($ch);
        return $this->_response($contents);
    }

/**
 * GETS url via CURL extension and retrieves response
 *
 * @param array $query Array of query string data to send
 * @return array The response
 */
    private function _getcurl($query = array()) {
        $ch = curl_init();
        
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->apiUrl . '?' . http_build_query($query),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                    'Auth-Portal: '.$this->apiID,
                    'Auth-Key: ' . $this->apiKey,
                    'Auth-Secret: ' . $this->apiSecret,
                    'Auth-Function:' . $this->function,
                )
            )
        );

        $contents = curl_exec ($ch);
        curl_close ($ch);
        return $this->_response($contents);
    }

/**
 * Response function to read if error or success
 *
 */
    private function _response($contents) {
        $result = json_decode($contents, true);
        if ($result) {
            if (isset($result['error'])) {
                return $result['error']['message'];
            }
            return $result;
        }
        return $contents;
    }

    private function curl($url = false, $query = array(), $httpheader = array()) {
    	if ($url) {
    		$ch = curl_init();
    		curl_setopt_array($ch, array(
	    			CURLOPT_URL => $url . '?' . http_build_query($query),
	    			CURLOPT_SSL_VERIFYPEER => false,
	    			CURLOPT_RETURNTRANSFER => true,
	    			CURLOPT_HTTPHEADER => $httpheader
	            )
	        );
	        $contents = curl_exec ($ch);
	        curl_close ($ch);
	        return $contents;
	    }
	    return false;

    }

	
}