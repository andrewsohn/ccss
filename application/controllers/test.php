<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->helper('url');
		$this->load->model('csMainMenu');
		$this->load->model('CsAdminEventTeaser');
		$this->load->model('CsAdminEventApplicant');
		$this->load->model('CsSns');
	}
	
	public function index()
	{
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->view('test');
		
	}
	
}