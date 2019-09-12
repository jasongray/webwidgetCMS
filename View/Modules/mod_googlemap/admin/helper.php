<?php 

/**
 *	mod_googlemap class
 *
 *
 */

class mod_googlemap  {

	public $ghost = 'maps.googleapis.com/maps/api/geocode/';

	public $format = 'xml';

/*
 * save function called from Module beforeSave used to manipluate the data array before saving to the database.
 *
 */
	public function save($data) {
		if (isset($data['params']['address']) && !empty($data['params']['address'])) {
			if (empty($data['params']['lat']) && empty($data['params']['lng'])) {
				$resp = $this->geocode($data['params']['address']);
				if ($resp) {
					$data['params']['lat'] = $resp['lat'];
					$data['params']['lng'] = $resp['lng'];
				}
			}
		}
		return $data;
	}
	
	private function geocode( $address = false ) {
	
		if( $address ) {
			
			$base_url = "http://" . $this->ghost . $this->format . "?sensor=false";
			$address = urlencode( $address );
			$xmlfile = simplexml_load_file( $base_url . "&address=" . $address );// or die( "url not loading" );
			
			$status = $xmlfile->status;
			$return = array();
			
			if ( $status == "OK" ) {
				
				$return['lat'] = (string)$xmlfile->result->geometry->location->lat;
				$return['lng'] = (string)$xmlfile->result->geometry->location->lng;
				
			}
			
			return $return;
			
		}
		
		return false;
		
	}

}