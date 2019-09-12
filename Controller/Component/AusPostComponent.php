<?php
/**
 *  Australia Post - Postage Calculator API 
 *
 *  Queries the Australia Post Postage API to return estimated postage based on dimensions provided
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
*   Australia Post Postage Component
*
*   @package        WebCart.Cake.Controller.Component
*
*/ 
class AusPostComponent extends Component {

/**
 *  Set of variables that can be set dynamically
 *  
 *  @var string
 */
    public $from_postcode;
    public $to_postcode;
    public $length;
    public $width;
    public $height;
    public $weight;
    public $service_code;
    public $option_code;
    public $suboption_code;
    public $extra_cover;

/**
 *  The Australia Post API key provided to you 
 *  when you registered at https://developers.auspost.com.au/apis
 *  
 *  @var string
 */
    public $apiKey = null; 

/**
 *  The Australia Post API url to query
 *  
 *  @var string
 */
    protected $apiUrl = null;

/**
 * Controller reference
 *
 * @var Controller
 */
    protected $_controller = null;

/**
 * Constants
 *
 * @var string
 */
    const AUS_LETTER_EXPRESS_SMALL = 'AUS_LETTER_EXPRESS_SMALL';
    const AUS_LETTER_REGULAR_LARGE = 'AUS_LETTER_REGULAR_LARGE';
    const AUS_PARCEL_REGULAR = 'AUS_PARCEL_REGULAR';
    const AUS_PARCEL_EXPRESS = 'AUS_PARCEL_EXPRESS';
    const AUS_PARCEL_COURIER = 'AUS_PARCEL_COURIER';
    const AUS_PARCEL_COURIER_SATCHEL_MEDIUM = 'AUS_PARCEL_COURIER_SATCHEL_MEDIUM';
    const INTL_SERVICE_AIR_MAIL = 'INTL_SERVICE_AIR_MAIL';
    const INTL_SERVICE_ECI_D = 'INTL_SERVICE_ECI_D';
    const INTL_SERVICE_ECI_M = 'INTL_SERVICE_ECI_M';
    const INTL_SERVICE_ECI_PLATINUM = 'INTL_SERVICE_ECI_PLATINUM';
    const INTL_SERVICE_EPI = 'INTL_SERVICE_EPI';
    const INTL_SERVICE_EPI_B4 = 'INTL_SERVICE_EPI_B4';
    const INTL_SERVICE_EPI_C5 = 'INTL_SERVICE_EPI_C5';
    const INTL_SERVICE_PTI = 'INTL_SERVICE_PTI';
    const INTL_SERVICE_RPI_B4 = 'INTL_SERVICE_RPI_B4';
    const INTL_SERVICE_RPI_DLE = 'INTL_SERVICE_RPI_DLE';
    const INTL_SERVICE_SEA_MAIL = 'INTL_SERVICE_SEA_MAIL';

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
 * @return void
 */
    public function initialize(Controller $controller) {
        $this->_controller = $controller;  
        $this->apiKey = Configure::read('MySite.AusPost.apikey');
        $this->apiUrl = 'https://test.npe.auspost.com.au/api/'; // https://auspost.com.au/api/';
        $this->service_code = self::AUS_PARCEL_REGULAR;
    }  

/**
 * Functon to get the postage cost of sending an item
 * based on the dimensions and weight of that item
 *
 * @param array $data Array of dimensions, weight
 * @param array $options Array of postage options
 * @return string Total postage cost or error message
 */
    public function postage($data = array(), $options = array()) {
        $_url = $this->apiUrl . 'postage/parcel/domestic/calculate.json';
        if (empty($data)) {
            $data['from_postcode'] = $this->from_postcode;
            $data['to_postcode'] = $this->to_postcode;
            $data['length'] = $this->length;
            $data['width'] = $this->width;
            $data['height'] = $this->height;
            $data['weight'] = $this->weight;
            $data['service_code'] = $this->service_code;
        }
        if (empty($options)) {
            $options['option_code'] = $this->option_code;
            $options['suboption_code'] = $this->suboption_code;
            $options['extra_cover'] = $this->extra_cover;
        }

        $result = $this->_getcurl($_url, array_merge($data, $options));

        if (isset($result['error'])) {
            $this->log($result['error']['errorMessage']);
            return $result['error']['errorMessage'];
        }

        return $result['postage_result']['total_cost'];

    }

/**
 * Set the variables for the data
 *
 * @param string $var The variable name
 * @param string $val The value to set
 * @return void
 */
    public function set($var, $val) {
        $this->{$var} = $val;
    }

/**
 * Sends url via CURL extension and retrieves json response
 *
 * @param string $url The url to send via CURL
 * @return array The decoded json response
 */
    private function _getcurl($url, $query) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($query));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Auth-Key: ' . $this->apiKey
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec ($ch);
        curl_close ($ch);
        return json_decode($contents,true);
    }

/**
 * GET url response via HTTP Socket
 *
 * @param string $url The url to send
 * @return array The decoded json response
 **/
    private function _get($url, $data) {
        $httpSocket = new HttpSocket();
        $response = $httpSocket->get($url, $data, array('header' => array('Auth-Key: ' . $this->apikey)));
        return json_decode($response, true);
    }


    public function debug() {
        pr($this);
    }

         
}
