<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('user_agent');
	}
	
	public function index(){
		if($this->session->userdata('ss_mb_id')){
			$data = $this->session->all_userdata();
			//$this->common->print_r2($data);
			
			foreach ($data as $key => $value) {
				if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
					$this->session->unset_userdata($key);
				}
			}
			//$this->session->sess_destroy();
			
			$this->load->helper('url');
			$url = site_url("login");
				
			if($this->agent->referrer())
				$url = $this->agent->referrer();
				
			$this->common->goto_url($url);
		}else{
			echo 'sss';
			//$this->common->alert('로그인 상태가 아닙니다.');
		}
	}
}