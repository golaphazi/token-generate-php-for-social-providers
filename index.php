<?php
session_start();
include __DIR__ . '/token.php';

$provider = isset($_GET['provider']) ? $_GET['provider'] : '';
if(!isset($_GET['provider'])){
     $provider = isset($_SESSION['provider']) ? $_SESSION['provider'] : '';
}

if(empty($provider)){
   return '';
}
//code: 3406
$callback = 'https://example.com/';

$config = [];
$config['provider'] = $provider;
if($provider == 'facebook'){
	$config['appid'] = '3406105760229481539'; 
	$config['secret'] = '2f3c039a7a81af9e9d9a8cc9bc7b3aaf3406';
	$_SESSION['provider'] = 'facebook';
}else if( $provider == 'instagram' ){
	$config['appid'] = 'f53f83f0f89c46a5a3406801274543d5cc85'; 
	$config['secret'] = 'c0b894da0bab426d3406b2892788543f39fc';
	$_SESSION['provider'] = 'instagram';
}else if( $provider == 'twitter' ){
	$config['appid'] = 'f53f83f0f89c46a5a801274543d5c3406c85'; 
	$config['secret'] = 'c0b894da0b3406ab426db2892788543f39fc'; 
	$_SESSION['provider'] = 'twitter';
}

$config['callback'] = $callback;
$config['return_url'] = $callback;

if( $provider == 'instagram' ){
  $url =  'https://instagram.com/oauth/authorize/?client_id=3a81a9fa2a064751b8c31385b91cc25c&scope=basic&redirect_uri=https://smashballoon.com/instagram-feed/instagram-token-plugin/?return_uri='.$callback.'admin.php?page=sb-instagram-feed&response_type=token&state='.$callback.'admin.php?page-sb-instagram-feed&hl=en';
    Util::redirect($url);
    
}

$obj = new Token( $config );
$obj->_authenticate(); 
if(isset($_GET['url']) && !empty($_GET['url']) ){
	if($obj->_isConnected()){
		$redirect = $_GET['url'];
		$redirect = str_replace('[token]', $obj->get_token(), $redirect) ;
		Util::redirect($redirect);
	}
}
?>

<html>
	<head>
		<style>
		.aceesstokenlink{text-align: center; margin-top: 15%;}
		.aceesstokenlink p{font-size: 30px;margin-bottom: 20px;}
		.aceesstokenlink a{
			text-align: center;
			font-size: 20px;
			text-decoration: none;
			padding: 10px 28px;
			background: #8BC34A;
			border-radius: 30px;
			color: #fff;
			margin-top: 20px;
		}
		.aceesstokenlink a:hover{
			color:#FFEB3B;
		}
		.aceesstoken h2 {
			margin-top: 10%;
			text-align: center;
		}
		input.instagram-access-token {
			font-size: 18px;
			padding: 14px;
			width: 100%;
			text-overflow: ellipsis;
			background: #fff;
			padding: 10px;
			border: 2px solid #ebeced;
			color: #222;
			max-width: 900px;

		}
		.token-input-wrapper {
			margin-top: 30px;
			margin-bottom: 30px;
			text-align:center;
		}
		.aceesstoken p{text-align:center;}
		</style>
	</head>
	<body>
		<?php if(!$obj->_isConnected()){?>
		<div class="aceesstokenlink">
			<p>Get Your Access Token</p>
			<p> Callback URL: <?php echo $config['return_url'];?></p>
			<a href=" <?php echo $obj->_setup_data();?> ">Get Access Token</a>
		</div>
		<?php }else{?>
		<div class="aceesstoken"><h2>Access Token: </h2>
			<p>Use this Access token in the appropriate field on your website or blog, and you should have a working widget.</p>
			<?php if( $provider == 'instagram' ){
				$id = explode('.', $obj->get_token());
				?>
			<div class="token-input-wrapper">
                <p> User Id: </p>
				<input class="instagram-access-token" onclick="this.setSelectionRange(0, this.value.length)" type="text" value="<?php echo current($id);?>">
             </div>
			<?php }?>
			<div class="token-input-wrapper">
                <p> Access Token: </p>
				<input class="instagram-access-token" onclick="this.setSelectionRange(0, this.value.length)" type="text" value="<?php echo $obj->get_token();?>">
             </div>
		</div>
		<?php }?>
	</body>
</html>
