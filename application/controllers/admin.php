<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('csMainMenu');
	}
	
	public function index(){
		$data = $this->session->all_userdata();
		print_r($data);
		
		$this->_header();
		
		if($this->session->userdata('ss_mb_id')){
			$mb_id = $this->session->userdata('ss_mb_id');
			/* 
			 * 회원 정보 가져오기
			 * $member = get_member($_SESSION['ss_mb_id']); */
			echo "logged in";
			$this->load->view('admin');
			//$this->load->view('main');
		}else{
			echo "not logged in";
			
			echo $this->router->fetch_class()."<br>";
			echo $this->router->fetch_method()."<br>";
			
			
			$admin_url = urlencode($this->current_full_url());
			//$this->load->helper('url');
			$this->load->helper('url');
			$this->session->set_flashdata('rurl', $admin_url);
			redirect('admin/login');
		}
		
		$this->_footer();
		
	}
	
	function login(){
		$rurl = $this->session->userdata('rurl');
		$data = array('title' => $title);
		$this->load->view('login',$data);
	}
	
	function current_full_url()
	{
		$CI =& get_instance();
	
		$url = $CI->config->site_url($CI->uri->uri_string());
		return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
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