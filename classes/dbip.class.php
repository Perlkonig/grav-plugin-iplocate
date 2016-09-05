<?php

namespace Grav\Plugin;

use Grav\Common\GPM\Response;

class DBIP {
	
	//the DBIP server
	var $host = 'http://api.db-ip.com/v2/{KEY}/{IP}';
		
	//initiate the DBIP vars
	var $ip = null;
	var $continentCode = null;
	var $continentName = null;
	var $countryCode = null;
	var $countryName = null;
	var $currencyCode = null;
	var $areaCode = null;
	var $languages = null;
	var $stateProv = null;
	var $district = null;
	var $city = null;
	var $geonameID = null;
	var $zipCode = null;
	var $latitude = null;
	var $longitude = null;
	var $gmtOffset = null;
	var $tz = null;
	var $isp = null;
	var $organization = null;
	
	function DBIP() {

	}
	
	function locate($cache, $key, $ip, $lang = "en-US") {
		
		$data = $cache->fetch('iplocate.dbip.'.$ip);
		if (! $data) {
			$host = str_replace( '{IP}', $ip, $this->host );
			$host = str_replace( '{KEY}', $key, $host );
			$response = Response::get($host);
			$data = json_decode($response, true);
			if (array_key_exists('error', $data)) {
				throw new \RuntimeException("Error communicating with DB-IP server. Server said '".$data['error']."'.");
			}
			$cache->save('iplocate.dbip.'.$ip, $data);
		}

		//set the DBIP vars
		$this->ip = $data['ipAddress'];
		$this->continentCode = $data['continentCode'];
		$this->continentName = $data['continentName'];
		$this->countryCode = $data['countryCode'];
		$this->countryName = $data['countryName'];
		$this->currencyCode = $data['currencyCode'];
		$this->areaCode = $data['phonePrefix'];
		$this->languages = $data['languages'];
		$this->stateProv = $data['stateProv'];
		$this->district = $data['district'];
		$this->city = $data['city'];
		$this->geonameID = $data['geonameId'];
		$this->zipCode = $data['zipCode'];
		$this->latitude = $data['latitude'];
		$this->longitude = $data['longitude'];
		$this->gmtOffset = $data['gmtOffset'];
		$this->tz = $data['timeZone'];
		$this->isp = $data['isp'];
		$this->organization = $data['organization'];
		return $this;
	}
}

?>