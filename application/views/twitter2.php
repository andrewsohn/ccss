<?php
session_start();
require './system/libraries/src/Twitter/TwitterOAuth.php';

$connection = new TwitterOAuth('NIILXSwqZ65evPP4bFfGFQLmz','d2z4sL59dDquqqWE0cY2LRMfg1CSEnQvkq5Ru97gCPQjNQQnEb');

$request_token = $connection->getRequestToken('http://ccss.hivelab.co.kr/ccss/index.php/twitterCB');

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