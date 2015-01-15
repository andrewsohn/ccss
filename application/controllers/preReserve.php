<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PreReserve extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('csMainMenu');
	}
	
	public function index()
	{
		$this->_header();
		$this->load->view('preReserve');
		//$this->load->view('main');
		$this->_footer();
	}
	function _header(){
		$title = $this->config->item('site_title');
		$data = array('title' => $title);
		$this->load->view('head');
		$menu_list = $this->csMainMenu->gets();
		$this->load->view('headSub', $data);
	}
	function _footer(){
		$this->load->view('tail');
		$this->load->view('tailSub');
	}
}