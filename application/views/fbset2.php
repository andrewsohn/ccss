<?php
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
	
	$id = '803684733038476';
	$secret = 'b843be21c28b20c652dde478bc7c93ed';
	
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
	}
?>
