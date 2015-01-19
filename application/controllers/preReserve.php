<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PreReserve extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->model('csMainMenu');
	}
	
	public function index()
	{
		$data = $this->session->all_userdata();
		$this->common->print_r2($data);
		$this->_header();
		$this->load->view('preReserve');
		//$this->load->view('main');
		$this->_footer();
	}
	function _header(){
		$title = $this->config->item('site_title');
		$data = array('title' => $title);
		$this->load->view('MainHead');
		$menu_list = $this->csMainMenu->gets();
		$this->load->view('MainHeadSub', $data);
	}
	function _footer(){
		$this->load->view('MainTail');
		$this->load->view('MainTailSub');
	}
}