<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('user_agent');
		$this->load->library('smarteditor');
		$this->load->model('CsAdminMenu');
		$this->load->model('CsAdminEventTeaser');
		$this->load->helper('url');
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
	
	public function EventTeaser($et_id=''){
		$this->_loginCheck();
		
		$data = array();
		
		$mb_id = $this->session->userdata('ss_mb_id');
		$member = $this->common->get_member($mb_id);
		$data['member'] = $member;

		$this->_header($member, $this->router->fetch_method());
		if($et_id === ''){
			$board_list = $this->CsAdminEventTeaser->gets();
			$i=0;
			while(isset($board_list[$i])){
				$board_list[$i]->href = site_url("admin").'/'.$this->router->fetch_method().'/'.$board_list[$i]->et_id;
				$i++;
			}
			
			$data['blist'] = $board_list;
			$this->load->view('AdminEventTeaserList', $data);
		}else{
			$data['view_mode'] = '';
			if($et_id != 'new'){
				$data['view_mode'] = 'u';
				$data['view'] = $this->CsAdminEventTeaser->get($et_id);
			}
			
			$this->load->view('AdminEventTeaserWrite', $data);
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
		$data = array();
		$data['member'] = $member;
		
		$data['baseUrl'] = site_url("admin");
		$data['am_code'] = $am_code;
		
		$title = $this->config->item('site_title');
		$data['title'] = $title;

		$this->load->view('AdminHeadSub', array('title'=>$title));
		$menu_list = $this->CsAdminMenu->gets();
		$data['mlist'] = $menu_list;
		
		$this->load->view('AdminHead', $data);
		
	}
	function _footer(){
		$this->load->view('AdminTail');
		$this->load->view('AdminTailSub');
	}
	
	function _loginCheck(){
		if(!$this->session->userdata('ss_mb_id')){
			$admin_url = $this->router->fetch_class().'/'.$this->router->fetch_method();
			$this->session->set_flashdata('rurl', $admin_url);
			
			redirect('/login/returnUrl/');
		}
	}
}