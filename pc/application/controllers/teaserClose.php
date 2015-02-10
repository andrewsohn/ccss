<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

require './system/libraries/autoload.php';
require './system/libraries/src/Twitter/TwitterOAuth.php';

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

class teaserClose extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('encrypt');
		$this->load->helper('url');
		$this->load->model('cs_main_menu');
		$this->load->model('cs_prereserve_applicant');
		$this->load->model('cs_sns');
	}
	
	public function index()
	{
		$id = $this->config->item('fb_id');
		$secret = $this->config->item('fb_secret');
		
		FacebookSession::setDefaultApplication($id, $secret);
		
		$helper = new FacebookRedirectLoginHelper(site_url('teaserClose'));
		$session = $helper->getSessionFromRedirect();
		
		if(isset($session)){
			$_SESSION['token'] = $session->getToken();
		}
		
		if (isset($_REQUEST['state']) && isset($_REQUEST['code'])) {
	        echo "<script>
	        		window.opener.showCont(0, 'fb');
	        		window.close();
	        </script>";
		} 
	}
	
	public function share()
	{
		$id = $this->config->item('fb_id');
		$secret = $this->config->item('fb_secret');
		
		FacebookSession::setDefaultApplication($id, $secret);
		
		$helper = new FacebookRedirectLoginHelper(site_url('teaserClose/share'));
		$session = $helper->getSessionFromRedirect();
		
		if(isset($session)){
			$_SESSION['token'] = $session->getToken();
		}
		
		if (isset($_REQUEST['state']) && isset($_REQUEST['code'])) {
	        echo "<script>
	        		window.opener.showCont(2, 'fb');
	        		window.close();
	        </script>";
		} 
	}
}