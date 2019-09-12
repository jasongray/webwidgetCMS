<?php
App::uses('Component', 'Controller');

class GooglemapComponent extends Component  {

	var $ghost = 'maps.googleapis.com/maps/api/geocode/';
	var $format = 'xml';
	
	
	function geocode( $address = false ) {
	
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

?>