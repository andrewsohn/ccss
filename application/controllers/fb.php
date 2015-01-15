<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fb extends CI_Controller {
	function __construct(){
		parent::__construct();
		//$this->load->model('fb_model');
	}
	
	public function index()
	{
		$this->load->view('fb');
	}
}