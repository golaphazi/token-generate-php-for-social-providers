<?php
include __DIR__ . '/providers/init.php';
include __DIR__ . '/providers/lib/util.php';
include __DIR__ . '/providers/lib/curl.php';

Class Token{
	private $providers;
	private $appid;
	private $secret;
	private $callback;
	private $return_url;
	private $status = false;
	
	private $config = [];
	private $adapter;
	private $url;
	private $token_access;
	
	public function __construct( array $type){
		
		$this->providers = isset($type['provider']) ? $type['provider'] : '';
		$this->appid = isset($type['appid']) ? $type['appid'] : '';
		$this->secret = isset($type['secret']) ? $type['secret'] : '';
		$this->callback = isset($type['callback']) ? $type['callback'] : '';
		$this->return_url = isset($type['return_url']) ? $type['return_url'] : '';
	}
	
	public function get_token(){
		return $this->token_access;
	}
	
	public function _authenticate(){
		
		if(isset($_GET['code'])  && !empty($_GET['code']) ){
			$this->get_access_token();
			return;
		}else if(isset($_GET['error'])){
			return $_GET['error'];
		}
		// setup data
		$this->_setup_data();
		
		Util::redirect($this->url);
	}
	
	public function _setup_data(){
		
		if( $this->providers == 'facebook' ){
			$state['site_url'] = $this->callback;
			$credi['response_type'] = 'code';
			$credi['client_id'] = $this->appid;
			$credi['redirect_uri'] = $this->return_url;
			$credi['scope'] = 'manage_pages';
			//$credi['scope'] = 'email, public_profile';
			$credi['state'] = base64_encode(json_encode($state));
			
			$this->adapter = New Facebook();
			$this->url = $this->adapter->_get_url( $credi );
			
		}else if( $this->providers == 'instagram' ){		
			$credi['client_id'] = $this->appid;
			$credi['redirect_uri'] = $this->return_url;
			$credi['scope'] = 'basic';
			$credi['response_type'] = 'code';	
			
			$this->adapter = New Instagram( );
			$this->url = $this->adapter->_get_url( $credi );
			
		}else if( $this->providers == 'twitter' ){		
		
			$credi['client_id'] = $this->appid;
			$credi['redirect_uri'] = $this->return_url;
			$credi['scope'] = 'read_public,write_public';
			$credi['response_type'] = 'code';	
			$credi['state'] = uniqid( '', true );
			
			$this->adapter = New Twitter( );
			$this->url = $this->adapter->_get_url( $credi );			
		}
		
		return $this->url;
	}
	public function _isConnected(){
		if(isset($_GET['code']) && !empty($_GET['code']) ){
			$this->status = true;
		}
		return $this->status;
	}
	
	protected function get_access_token(){
		if( $this->providers == 'facebook' ){
			$this->adapter = New Facebook( );
		}else if( $this->providers == 'instagram' ){
			$this->adapter = New Instagram( );
		}else if( $this->providers == 'twitter' ){
			$this->adapter = New Twitter( );	
		}
		$this->token_access = $this->adapter->get_token($this->appid, $this->secret, $this->return_url);
		return $this->token_access;
	}
	
	public function _get_authentic_url(){
		return $this->url;
	}
}
