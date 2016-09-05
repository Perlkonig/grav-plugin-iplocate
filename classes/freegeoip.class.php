<?php

namespace Grav\Plugin;

use Grav\Common\GPM\Response;

class freeGeoIP {
	
	//the freeGeoIP server
	var $host = 'http://freegeoip.net/json/{IP}';
		
	//initiate the freeGeoIP vars
	var $ip = null;
	var $countryCode = null;
	var $countryName = null;
	var $regionCode = null;
	var $regionName = null;
	var $city = null;
	var $zipCode = null;
	var $tz = null;
	var $metroCode = null;
	var $latitude = null;
	var $longitude = null;
	
	function freeGeoIP() {

	}
	
	function locate($cache, $ip) {
		
		$data = $cache->fetch('iplocate.freegeoip.'.$ip);
		if (! $data) {
			$host = str_replace( '{IP}', $ip, $this->host );
			$response = Response::get($host);
			$data = json_decode($response, true);
			$cache->save('iplocate.freegeoip.'.$ip, $data);
		}
		
		//set the freeGeoIP vars
		$this->ip = $data['ip'];
		$this->countryCode = $data['country_code'];
		$this->countryName = $data['country_name'];
		$this->regionCode = $data['region_code'];
		$this->regionName = $data['region_name'];
		$this->city = $data['city'];
		$this->zipCode = $data['zip_code'];
		$this->tz = $data['time_zone'];
		$this->metroCode = $data['metro_code'];
		$this->latitude = $data['latitude'];
		$this->longitude = $data['longitude'];
		return $this;
	}
}

?>