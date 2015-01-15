<?php
session_start();
require './system/libraries/src/Twitter/TwitterOAuth.php';

$_SESSION["oauth_verifier"] = $_GET["oauth_verifier"];

$connection = new TwitterOAuth('NIILXSwqZ65evPP4bFfGFQLmz','d2z4sL59dDquqqWE0cY2LRMfg1CSEnQvkq5Ru97gCPQjNQQnEb', $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$credentials = $connection->getAccessToken($_GET["oauth_verifier"]);

$image = file_get_contents('./test2.png');
$params = array(
	'media[]'  => $image, 
	'status'  => 'image test update'
);

$response =$connection->post('statuses/update_with_media', $params, true);
//var_dump($response);

echo '<br><br><br><p>완료</p>';
?>