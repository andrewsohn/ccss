<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twitter extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->helper('url');
	}
	
	public function index()
	{
		$this->load->view('twitter');
	}
	
	public function preReserve()
	{
		$this->load->view('twitterPR');
	}
	
	public function close()
	{
		$this->load->view('twitterClose');
	}
	public function close2()
	{
		$this->load->view('twitterClose2');
	}
	public function verifier()
	{
		session_start();
		if(isset($_SESSION['oauth_token']) && isset($_REQUEST['oauth_verifier'])){
			$_SESSION["oauth_verifier"] = $_GET["oauth_verifier"];
		}
		echo "<script>
					window.opener.getTextFocus(2);
	        		window.close();
	        </script>";
		
	}
	public function verifier2()
	{
		session_start();
		if(isset($_SESSION['oauth_token']) && isset($_REQUEST['oauth_verifier'])){
			$_SESSION["oauth_verifier"] = $_GET["oauth_verifier"];
		}
		
		$param = 1;
		if(isset($_REQUEST['share']))
			$param = 3;
		
		echo "<script>
					window.opener.showCont(".$param.", 'tt');
	        		window.close();
	        </script>";
	
	}
}