<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('user_agent');
		$this->load->library('smarteditor');
		$this->load->model('CsAdminMenu');
		$this->load->model('CsAdminEventTeaser');
		$this->load->model('CsAdminEventApplicant');
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
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
			}
			
			$this->load->view('AdminEventTeaserWrite', $data);
		}
		
		$this->_footer();
	}
	
	public function EventApplicant($ea_id=''){
		$this->_loginCheck();
		
		$data = array();
		
		$mb_id = $this->session->userdata('ss_mb_id');
		$member = $this->common->get_member($mb_id);
		$data['member'] = $member;

		$this->_header($member, $this->router->fetch_method());
		if($ea_id === ''){
			$board_list = $this->CsAdminEventApplicant->gets();
			$i=0;
			while(isset($board_list[$i])){
				$board_list[$i]->href = site_url("admin").'/'.$this->router->fetch_method().'/'.$board_list[$i]->ea_id;
				$i++;
			}
			
			$data['blist'] = $board_list;
			$this->load->view('AdminEventApplyList', $data);
		}else{
			$data['view_mode'] = '';
			if($ea_id != 'new'){
				$data['view_mode'] = 'u';
				$data['view'] = $this->CsAdminEventApplicant->get($ea_id);
			}
			
			$this->load->view('AdminEventApplyWrite', $data);
		}
		
		$this->_footer();
	}
	
	public function eventTeaserAction(){
		$this->_loginCheck();
		
		//필수 필드 Validation [start]--------------------
		$msg = array();
		
		$et_subject = '';
		if($this->input->post('et_subject', TRUE)){
			$et_subject = trim($this->input->post('et_subject', TRUE));
		}else{
			$msg[] = '<strong>이벤트 제목</strong>을 입력하세요.';
		}
		
		$et_content = '';
		if($this->input->post('et_content', TRUE)){
			$et_content = trim($this->input->post('et_content', TRUE));
		}else{
			$msg[] = '<strong>이벤트 내용</strong>을 입력하세요.';
		}
		
		$et_mode = 0;
		if($this->input->post('et_mode', TRUE) != ''){
			echo $et_mode = trim($this->input->post('et_mode', TRUE));
		}else{
			$msg[] = '<strong>이벤트 상태</strong>를 입력하세요.';
		}
		
		$msg = implode('<br>', $msg);
		if ($msg) {
			$this->common->alert($msg);
			exit;
		}
		//필수 필드 Validation [end]--------------------
				
		$et_opendate = '';
		if($this->input->post('et_opendate', TRUE)){
			$et_opendate = trim($this->input->post('et_opendate', TRUE));
			if($this->input->post('et_openhr', TRUE)){
				$et_opendate .= ' '.trim($this->input->post('et_openhr', TRUE));
				if($this->input->post('et_openmin', TRUE)){
					$et_opendate .= ':'.trim($this->input->post('et_openmin', TRUE)).':00';
				}
			}
		}
		echo $et_opendate;
		
		$et_closedate = '';
		if($this->input->post('et_closedate', TRUE)){
			$et_closedate = trim($this->input->post('et_closedate', TRUE));
			if($this->input->post('et_closehr', TRUE)){
				$et_closedate .= ' '.trim($this->input->post('et_closehr', TRUE));
				if($this->input->post('et_closemin', TRUE)){
					$et_closedate .= ':'.trim($this->input->post('et_closemin', TRUE)).':00';
				}else{
					$et_closedate .= ':00:00';
				}
			}else{
				$et_closedate .= ' 00:00:00';
			}
		}
		echo $et_closedate;
		
		$et_link = '';
		if($this->input->post('et_link', TRUE)){
			echo $et_link = trim($this->input->post('et_link', TRUE));
		}
		
		$this->CsAdminEventTeaser->insert();
		
		/* if (!$mb_id || !$mb_password){
			$this->common->alert('회원아이디나 비밀번호가 공백이면 안됩니다.');
			exit;
		}
		
		$this->common->print_r2($mb);
		
		$this->load->helper('url');
		redirect($this->session->flashdata('rurl')); */
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
		//$this->common->print_r2($menu_list);
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