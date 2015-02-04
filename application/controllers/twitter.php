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
}