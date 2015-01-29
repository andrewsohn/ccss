<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PreReserve extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->helper('url');
		$this->load->model('csMainMenu');
		$this->load->model('CsPreApplicant');
		$this->load->model('CsSns');
	}
	
	public function index()
	{
		$data = $this->session->all_userdata();
		$this->_header();
		
		/* 
		$clist = $this->CsPreApplicant->getList();
		$data['clist'] = $clist;
		 */
		
		$this->load->view('preReserve', $data);
		
		$this->_footer();
	}
	function _header(){
		$title = $this->config->item('site_title');
		$data = array('title' => $title);
		$menu_list = $this->csMainMenu->gets();
		$this->load->view('MainHeadSub', $data);
		$this->load->view('MainHead');
	}
	function _footer(){
		$this->load->view('MainTail');
		$this->load->view('MainTailSub');
	}
}