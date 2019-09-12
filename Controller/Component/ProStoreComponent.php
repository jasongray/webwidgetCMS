<?php
/**
 *  ProStore API query class
 *
 *  Queries the ProStore database
 *
 *  Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *
 *  Licensed under The MIT license
 *  Redistributions of this file must retain the above copyright notice
 *
 *  @copyright      Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *  @package        WebCart
 *  @author         Jason Gray
 *  @version        1.1
 *  @license        http://www.opensource.org/licenses/mit-license.php MIT License
 *
 */

App::uses('HttpSocket', 'Network/Http');


/**
*   ProStore Component
*
*   @package        WebCart.Cake.Controller.Component
*
*/ 
class ProStoreComponent extends Component {

/**
 *  ProStore API portal ID
 *  
 *  @var string
 */
    public $apiID = null; 

/**
 *  ProStore API key
 *  
 *  @var string
 */
    public $apiKey = null; 

/**
 *  ProStore API secret
 *  
 *  @var string
 */
    public $apiSecret = null; 

/**
 *  The ProStore API url to query
 *  
 *  @var string
 */
    protected $apiUrl = null;

/**
 *  The function in the ProStore API class to call
 *  
 *  @var string
 */
    protected $function = null;

/**
 * Controller reference
 *
 * @var Controller
 */
    protected $_controller = null;

/**
 * Constructor
 *
 * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
 * @param array $settings Array of configuration settings.
 */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        $this->_controller = $collection->getController();
        parent::__construct($collection, $settings);
    }

/**
 * Initialise the component and settings
 *
 *   'apiUrl'
 *   'apiKey'
 *   'apiSecret'
 *
 * @return void
 */
    public function initialize(Controller $controller) {
        $this->_controller = $controller;  
        $this->apiID = Configure::read('MySite.portal');
        $this->apiKey = Configure::read('MySite.ProStore.apikey');
        $this->apiSecret = Configure::read('MySite.ProStore.secret');
        $this->apiUrl = Configure::read('MySite.ProStore.url');
    }  

/**
 * Magic method to send data to ProStore API
 * Determines which curl method to use then checks the method exists
 * and then executes that method, returning the result
 *
 * @param string $method The method called
 * @param array $args An array of arguments sent when calling the method
 * @return mixed Return of information from the function called.
 */
    public function __call($method, $args) {
        $this->function = $method;
        $method = '_getcurl';
        if (preg_match('/post|save|do|read|get/', $this->function)) {
            $method = '_postcurl';
        }
        if (method_exists($this, $method)) {
            return $this->$method($args);
        } 
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
 * POSTS url via CURL extension and retrieves response
 *
 * @param array $data Array of data to send via POST
 * @return array The response
 */
    private function _postcurl($data = array()) {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                    'Auth-Portal: '.$this->apiID,
                    'Auth-Key: ' . $this->apiKey,
                    'Auth-Secret: ' . $this->apiSecret,
                    'Auth-Function:' . $this->function,
                ),
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
        $result = $this->json($contents, true);
        if ($result) {
            if (isset($result['Error'])) {
                throw new Exception ($result['Error']['Message'], $result['Error']['Type']);
            }
            return $result;
        }
        return $contents;
    }

/**
 * Check if JSON returned by server
 *
 */
    private function json($str, $assoc = false) {
        $result = json_decode($str, $assoc);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid
                break;
            case JSON_ERROR_DEPTH:
                $error = 'Maximum stack depth exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Underflow or the modes mismatch.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Unexpected control character found.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // only PHP 5.3+
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }
        if($error !== '') {
            $this->log($error, 'prostore');
            return false;
        }
        return $result;

    }
         
}
