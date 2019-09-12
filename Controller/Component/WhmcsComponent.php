<?php
/**
 *  WHMCS API 
 *
 *  Wrapper for curl-based API connection to WHMCS API
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
App::uses('AppModel', 'Model');

/**
 *   WHMCS API Component
 *
 *   @package        WebCart.Cake.Controller.Component
 *
 */ 
class WhmcsComponent extends Component {

/**
 * URL of WHMCS api.
 *
 * @var string
 * @access public
 */
    public $api_url = null;

/**
 * Admin username for WHMCS access
 *
 * @var string
 * @access public
 */
    public $api_username = null;

/**
 * Admin password for WHMCS access
 *
 * @var string
 * @access public
 */
    public $api_password = null;

/**
 * Settings array
 *
 * @var array
 * @access private
 */
    public $settings = array();

/**
 * Constructor
 *
 * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
 * @param array $settings Array of configuration settings.
 */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        //$this->Controller = $collection->getController();
        Configure::load('whmcs');
        $this->settings = Configure::read('Whmcs');
        parent::__construct($collection, $this->settings);
    }

/**
 * Initialise the component and settings
 *
 * @return void
 */
    public function initialize(Controller $controller) {
        $this->Controller = $controller;
        $this->Controller->set('WhmcsComponent', new WhmcsComponent(new ComponentCollection()));
    }  

/**
 * Call the WHMCS api function. Throws warnings and returns false on any errors, otherwise returns array of the results from WHMCS
 *
 * Example to add a client to the database use:
 *
 *    $this->Whmcs->api('addclient', Array(
 *        "firstname" => "test",
 *        "lastname" => "test",
 *        "companyname" => "test",
 *        "email" => "test@test.com",
 *        "address1" => "test",
 *        "address2" => "test",
 *        "city" => "test",
 *        "state" => "test",
 *        "postcode" => "1231231",
 *        "country" => "test",
 *        "phonenumber" => "5551212",
 *        "password2" => "test",
 *    ));
 *
 * @param string $action WHMCS action to be performed. ("addclient" for example)
 * @param array $data Array of data to be sent to WHMCS.
 * @access public
 */
    public function api ($action = false, $data = array()) {
        if (!isset($action)){ return false;}

        if (!isset($this->settigs['api_username']) || !isset($this->settigs['api_password'])){ return false;}

        // Data to be sent to WHMCS.
        $_pass_data = array_merge(array(
            'username' => $this->settings['api_username'], 
            'password' => md5($this->settings['api_password']), 
            'action' => $action), 
        $data);

        // Connect to WHMCS
        $_result = $this->Http->post($this->settings['api_url'], http_build_query($_pass_data));

        if ($_result === null || $_result === false || preg_match('/^(4|5)/', $code)) {
            trigger_error(__('Error from HttpSocket: HTTP code '.$code.($this->Http->lastError()?', HttpSocket error code: '.$this->Http->lastError():''), true), E_USER_WARNING);
            return false;
        }

        // Parse returned data from WHMCS
        $_return = array();
        foreach (explode(';', $_result) as $x) {
            parse_str($x, $a);
            $_return = array_merge($_return, $a);
        }

        // Check for invalid dataset
        if (count($_return) == 0) {
            trigger_error(__('WHMCS returned empty result set.', true), E_USER_WARNING);
            return false;
        }

        // Check for WHMCS result error
        if ($_return['result'] === 'error' && preg_match('/^Authentication/', $_return['message'])) { 
            trigger_error(__('WHMCS returned error: '.$_return['message'], true), E_USER_WARNING);
            return false;
        }

        // Return WHMCS data
        return (array)$_return;

    }

/**
 * Request domain array to render a table view
 *
 * @param void
 * @access public 
 * @return array
 */
    public function getdomains() {
        $return = false;
        $this->Model = ClassRegistry::init('AppModel');
        $this->Model->useTable = false;
        $this->Model->setDataSource('whmcs');
        $result = $this->Model->query("SELECT extension AS tld, type, msetupfee, code FROM tbldomainpricing AS d
            INNER JOIN tblpricing AS t ON t.relid = d.id
            INNER JOIN tblcurrencies AS c ON c.id = t.currency 
        WHERE t.type IN ('domainregister','domaintransfer','domainrenew') ORDER BY d.id ASC");
        if ($result) {
            $return = array();
            foreach ($result as $r) {
                $return = array_merge_recursive($return, array($r['d']['tld'] => array($r['t']['type'] => $r['t']['msetupfee'])));
            }
        }
        return $return;
    }

/**
 * Request hosting array to render a table view
 *
 * @param void
 * @access public 
 * @return array
 */
    public function gethosting() {
        $return = false;
        $this->Model = ClassRegistry::init('AppModel');
        $this->Model->useTable = false;
        $this->Model->setDataSource('whmcs');
        $result = $this->Model->query("SELECT p.id, p.name, p.description, t.monthly 
            FROM tblproducts AS p
            INNER JOIN tblpricing AS t ON t.type='product' AND t.relid = p.id
            INNER JOIN tblcurrencies AS c ON c.id = t.currency
            WHERE p.hidden = 0 AND p.retired = 0 AND p.type ='hostingaccount'
            ORDER BY p.order ASC");
        if ($result) {
            $return = array();
            foreach ($result as $r) {
                $return = array_merge_recursive($return, array($r['p']['id'] => array('id' => $r['p']['id'], 'image' => 'icon-'.str_replace(' ', '-', strtolower($r['p']['name'])).'.png', 'name' => $r['p']['name'], 'description' => $r['p']['description'], 'mfee' => $r['t']['monthly'])));
            }
        }
        return $return;
    }

}
?>