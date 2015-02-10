<?php
session_start();
require './system/libraries/src/Twitter/TwitterOAuth.php';

$id = $this->config->item('tt_id');
$secret = $this->config->item('tt_secret');

$connection = new TwitterOAuth($id,$secret);

$share = '';
if(isset($_REQUEST['share']))
	$share = '?share='.$_REQUEST['share'];
$request_token = $connection->getRequestToken(site_url('twitter/verifier2').$share);

$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

switch ($connection->http_code){
	case 200:
		$url = $connection->getAuthorizeURL($token);
		header('Location:'.$url);
		break;
	default:
		echo "Oops ! Something went wrong Check in the Twitter Docs for this HTTP CODE". $connection->http_code;
}


?>