<?php

namespace Grav\Plugin;

use Grav\Common\GPM\Response;

class IPInfo {
	
	//the IPInfo server
	var $host = 'http://ipinfo.io/{IP}';
		
	//initiate the IPInfo vars
	var $ip = null;
	var $city = null;
	var $regionCode = null;
	var $countryCode = null;
	var $latitude = null;
	var $longitude = null;
	var $org = null;
	
	function IPInfo() {

	}
	
	function locate($cache, $ip) {
		
		$data = $cache->fetch('iplocate.ipinfo.'.$ip);
		if (! $data) {
			$options = [
				'curl' => [
					CURLOPT_USERAGENT => 'curl',
				],
				'fopen' => [
					'user_agent' => 'curl',
				]
			];
			$host = str_replace( '{IP}', $ip, $this->host );
			//dump($host);
			$response = Response::get($host, $options);
			//dump($response);
			$data = json_decode($response, true);
			$cache->save('iplocate.ipinfo.'.$ip, $data);
		}
		
		//set the IPInfo vars
		$this->ip = $data['ip'];
		$this->countryCode = $data['country'];
		$this->regionCode = $data['region'];
		$this->city = $data['city'];
		$this->org = $data['org'];
		$loc = explode(',', $data['loc']);
		$this->latitude = $loc[0];
		$this->longitude = $loc[1];
		return $this;
	}
}

?>