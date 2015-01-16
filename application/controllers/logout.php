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
			foreach ($data as $key => $value) {
				if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
					$this->session->unset_userdata($key);
				}
			}
			//$this->session->sess_destroy();
			
				//$this->common->goto_url($this->agent->referrer());
				echo $this->agent->referrer();
			//redirect($_SERVER['HTTP_REFERER']);
		}else{
			echo 'sss';
			//$this->common->alert('로그인 상태가 아닙니다.');
		}
	}
}