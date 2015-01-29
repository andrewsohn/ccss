<?php
	session_start();
	
	require './system/libraries/autoload.php';
	
	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\GraphUser;
	
	use Facebook\GraphSessionInfo;
	
	$id = '348697705319104';
	$secret = '72acca56f341803ddada56ecefb4ad11';
	
	FacebookSession::setDefaultApplication($id, $secret);
	
	$helper = new FacebookRedirectLoginHelper('http://ccss.hivelab.co.kr/ccss/index.php/fb');
	$session = $helper->getSessionFromRedirect();
	
	if(isset($session)){	
// 		$_SESSION['token'] = $_GET['code'];
		$_SESSION['token'] = $session->getToken();
		$request = new FacebookRequest($session, 'GET', '/me');		
		$response = $request->execute();	
		$graph = $response->getGraphObject(GraphUser::className());	
		echo "Hi " . $graph->getName();
?>
<script>
	setTimeout(function() {
		window.location.href = 'http://ccss.hivelab.co.kr/ccss/index.php/fbcallback';
	}, 500);
</script>
<?php
	} else {
		$scope = array('publish_actions');
		echo "<a href = " . $helper->getLoginUrl($scope) . ">Login With Facebook</a>";
	}
	
?>