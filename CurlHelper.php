<?php
namespace Dottyfix\Tools\CurlHelper;

class CurlHelper {
	
	protected $apiBaseUrl = '';
	protected $apikey = '';
		
	function __construct($apiBaseUrl, $apiKey) {
		$this->apiBaseUrl =  $apiBaseUrl;
		$this->apiKey =  $apiKey;
	}
	
	function exec($path, $datas = [], $method = 'post') {
		
		$apiUrl = $this->apiBaseUrl.$path;
		$header = [
			'Accept: application/json',
			'Content-Type: application/json',
		];
		if($this->apiKey) {
			$header[] = 'Api-Key: ' . $this->apiKey;
			$header[] = 'Authorization: Bearer ' . $this->apiKey;
		}
		
		$ch = curl_init($apiUrl);
		//curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datas));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_close($ch);
		$output = curl_exec($ch);
		$infos = curl_getinfo($ch);
		$this->infos = array_merge($infos, ['output' => $output, 'response' => json_decode($output, true)]);
		return in_array($infos['http_code'], [200, 201, 202, 204]);
	}
}
