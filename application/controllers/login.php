<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('user_agent');
	}
	
	public function index(){
		if($this->session->userdata('ss_mb_id')){
			$this->load->helper('url');
			$url = site_url("");
			
			if($this->agent->referrer())
				$url = $this->agent->referrer();
			
			$this->common->goto_url($url);
		}
		
		$data = $this->session->all_userdata();
		$this->common->print_r2($data);
		$rurl = 'admin/index';
		if($this->session->flashdata('rurl')){
			$rurl = $this->session->flashdata('rurl');
			$this->session->set_flashdata('rurl', $rurl);
		}
		
		$data = array('rurl'=>$rurl);
		$this->_header();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->view('login', $data);
		
		$this->_footer();
	}
	
	function returnUrl(){
		$rurl = 'admin/index';
		if($this->session->flashdata('rurl')){
			$rurl = $this->session->flashdata('rurl');
			$this->session->set_flashdata('rurl', $rurl);
		}
		
		$data = array('rurl'=>$rurl);
		$this->_header();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->view('login', $data);
		
		$this->_footer();
	}
	
	function action(){
		$CI =& get_instance();
		$login_url = $CI->config->site_url('login');
		
		if($this->session->flashdata('rurl')){
			$this->session->set_flashdata('rurl', $this->session->flashdata('rurl'));
		}
		
		$mb_id = '';
		if($this->input->post('mb_id', TRUE)){
			$mb_id = trim($this->input->post('mb_id', TRUE));
		}
		
		$mb_password = '';
		if($this->input->post('mb_password', TRUE)){
			$mb_password = trim($this->input->post('mb_password', TRUE));
		}
		
		if (!$mb_id || !$mb_password){
			$this->common->alert('회원아이디나 비밀번호가 공백이면 안됩니다.', $login_url);
			exit;
		}
		
		//$mb = $this->Member->get($mb_id);
		$mb = $this->common->get_member($mb_id);
		
		if (!$mb->mb_id || ($this->common->sql_password($mb_password) != $mb->mb_password)) {
			$this->common->alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.', $login_url);
			exit;
		}

		$this->common->print_r2($mb);
		$newdata = array(
				'ss_mb_id'=>$mb->mb_id,
				'ss_mb_key'=>md5($mb->mb_datetime . $this->input->ip_address() . $this->agent->agent_string())
		);
		$this->session->set_userdata($newdata);
		
		$this->load->helper('url');
		redirect($this->session->flashdata('rurl'));
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
		$this->load->view('MainHeadSub', $data);
	}
	function _footer(){
		$this->load->view('MainTailSub');
	}
}