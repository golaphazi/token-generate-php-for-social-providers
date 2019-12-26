<?php
Class Instagram{
	public $url;
	public $curl;
	public $access_code;
	
	private $tokenURL = 'https://api.instagram.com/oauth/authorize'; 
	
	protected $accessTokenUrl = 'https://api.instagram.com/oauth/access_token';

	public function __construct(  ){
		$this->curl = new Curl();	
	}
	
	public function _get_url( $config ){
		$this->url = $this->tokenURL.'?'.http_build_query($config, '&');	
		return $this->url;
	}
	
	public function get_token( $api_key, $api_secret, $callback){
		$this->access_code = isset($_GET['code']) ? $_GET['code'] : '';
		if(strlen($this->access_code) > 1){
			$credentials = $api_key . ':' . $api_secret;
			$toSend 	 = base64_encode($credentials);
			
			$header = [
					'Authorization' => 'Basic ' . $toSend,
					'Content-Type' 	=> 'application/x-www-form-urlencoded;charset=UTF-8'
				];
			$body = [ 'grant_type' => 'authorization_code', 'code' => $this->access_code, 'client_id' => $api_key, 'client_secret' => $api_secret, 'redirect_uri' => $callback ];
			
			$reoponse = $this->curl->request($this->accessTokenUrl, 'POST', $body, $header);
			$reoponse = @json_decode($reoponse, true);
			
			if( isset($reoponse['access_token']) ){
				return $reoponse['access_token'];
			} else{
				return isset($reoponse['error_message']) ?  'Error Found' : '';
			}
			
		}
	}
}