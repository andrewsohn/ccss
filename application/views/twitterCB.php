<?php
session_start();
require './system/libraries/src/Twitter/TwitterOAuth.php';

$_SESSION["oauth_verifier"] = $_GET["oauth_verifier"];

$id = $this->config->item('tt_id');
$secret = $this->config->item('tt_secret');

$connection = new TwitterOAuth($id,$secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
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