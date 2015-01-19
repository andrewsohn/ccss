<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('user_agent');
		$this->load->model('CsAdminMenu');
	}
	
	public function index(){
		$data = $this->session->all_userdata();
		//$this->common->print_r2($data);
		echo $this->agent->referrer();
		
		$this->_loginCheck();
		
		$mb_id = $this->session->userdata('ss_mb_id');
		$member = $this->common->get_member($mb_id);
		//$this->common->print_r2($member);
		$this->_header($member);
		$this->load->view('admin', array('member'=>$member));
		$this->_footer();
		
		
	}
	
	public function EventTeaser($id=''){
		$this->load->model('CsAdminEventTeaser');
		
		$data = array();
		$this->_loginCheck();
		
		$mb_id = $this->session->userdata('ss_mb_id');
		$member = $this->common->get_member($mb_id);
		$data['member'] = $member;

		$this->_header($member, $this->router->fetch_method());
		if($id === ''){
			$board_list = $this->CsAdminEventTeaser->gets();
			$data['blist'] = $board_list;
			
			$this->load->view('Admin'.$this->router->fetch_method().'List', array('member'=>$member));
		}else{
			$data['id'] = $id;
			
			$this->load->view('Admin'.$this->router->fetch_method().'Write', $data);
		}
		
		$this->_footer();
	}
	
	function current_full_url()
	{
		$CI =& get_instance();
	
		$url = $CI->config->site_url($CI->uri->uri_string());
		return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
	}
	
	function _header($member=array(),$am_code=''){
		$this->load->helper('url');

		$data = array();
		$data['member'] = $member;
		
		$data['baseUrl'] = site_url("admin");
		$data['am_code'] = $am_code;
		
		$title = $this->config->item('site_title');
		$data['title'] = $title;

		$menu_list = $this->CsAdminMenu->gets();
		$data['mlist'] = $menu_list;
		
		//$this->common->print_r2($data);
		
		$this->load->view('AdminHead', $data);
		$this->load->view('AdminHeadSub', array('title'=>$title));
	}
	function _footer(){
		$this->load->view('AdminTail');
		$this->load->view('AdminTailSub');
	}
	
	function _loginCheck(){
		if(!$this->session->userdata('ss_mb_id')){
			echo "not logged in";
			
			echo $this->router->fetch_class()."<br>";
			echo $this->router->fetch_method()."<br>";
			
			$admin_url = $this->router->fetch_class().'/'.$this->router->fetch_method();
			//$this->load->helper('url');
			$this->load->helper('url');
			$this->session->set_flashdata('rurl', $admin_url);
			
			redirect('/login/returnUrl/');
		}
	}
}