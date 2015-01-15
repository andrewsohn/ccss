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
	
	if(isset($_SESSION['token'])){	
		try {
			$session = new FacebookSession($_SESSION['token'] );
			$response = (new FacebookRequest(
					$session, 'POST', '/me/photos', array(							
							'source' => new CURLFile(realpath('./test2.png'), 'image/png', 'FDAFSDAFASDFSA.png'),
							'message' => 'User provided message11'
					)
			))->execute()->getGraphObject();
			
		} catch(FacebookRequestException $e) {
		
			echo "Exception occured, code: " . $e->getCode();
			echo " with message: " . $e->getMessage();
		
		}
// 		$response = $request->execute();	
// 		$graph = $response->getGraphObject(GraphUser::className());	
// 		echo "Hi " . $graph->getName();
	}
?>
