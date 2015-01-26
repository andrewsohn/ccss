<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('user_agent');
		$this->load->library('smarteditor');
		$this->load->library('pagination');
		$this->load->model('CsAdminMenu');
		$this->load->model('CsAdminEventTeaser');
		$this->load->model('CsAdminEventApplicant');
		$this->load->helper('url');
	}
	
	public function index(){
		$data = $this->session->all_userdata();
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
		
		$data = $this->common->getQSTR(array());
		if(isset($data)){
			$qstr = '?'.$this->common->getArrQstr($data);
		}
		
		$data['qstr'] = $qstr;
		
		$mb_id = $this->session->userdata('ss_mb_id');
		$member = $this->common->get_member($mb_id);
		$data['member'] = $member;

		$this->_header($member, $this->router->fetch_method());
		if($et_id === ''){
			if(isset($_REQUEST['page'])){
				if($_REQUEST['page']){
					$page = (int)$_REQUEST['page'];
				}else{
					$page = 1;
				}
				$board_list = $this->CsAdminEventTeaser->getList($page);
				$config['cur_page'] = $page;
			}else{
				$board_list = $this->CsAdminEventTeaser->getList();
				$config['cur_page'] = 1;
			}
			
			$i=0;
			while(isset($board_list[$i])){
				$board_list[$i]->href = site_url("admin").'/'.$this->router->fetch_method().'/'.$board_list[$i]->idx.$qstr;
				$i++;
			}
			
			$data['blist'] = $board_list;
			
			$config['base_url'] = site_url('admin/EventTeaser');
			$config['total_rows'] = $this->CsAdminEventTeaser->totalRows(); 
			$config['per_page'] = 20;
			
			$this->pagination->initialize($config);
			
			$this->load->view('AdminEventTeaserList', $data);
		}else{
			$data['view_mode'] = '';
			if($et_id != 'new'){
				$data['idx'] = $et_id;
				$data['view_mode'] = 'u';
				$data['view'] = $this->CsAdminEventTeaser->get($et_id);
				
			}
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
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
			$data['etList'] = $this->CsAdminEventTeaser->getListLive();
				
			$data['et_id'] = '';
			if(isset($_REQUEST['et_id'])){
				$is_et_id = false;
			
				$data['et_id'] = (int)$_REQUEST['et_id'];
				for($i=0; $i<count($data['etList']); $i++){
					if($data['et_id'] == $data['etList'][$i]->idx){
						$is_et_id = true;
					}
						
				}
			
				if(!$is_et_id){
					$this->common->alert('카테고리가 존재하지 않습니다.');
				}
			}
			
			if(isset($_REQUEST['page'])){
				if($_REQUEST['page']){
					$page = (int)$_REQUEST['page'];
				}else{
					$page = 1;
				}
				$board_list = $this->CsAdminEventApplicant->getList($data['et_id'], $page);
				$config['cur_page'] = $page;
			}else{
				$board_list = $this->CsAdminEventApplicant->getList($data['et_id']);
				$config['cur_page'] = 1;
			}
			
			
			/* $i=0;
			while(isset($board_list[$i])){
				$board_list[$i]->href = site_url("admin").'/'.$this->router->fetch_method().'/'.$board_list[$i]->ea_id;
				$i++;
			} */
			
			$data['blist'] = $board_list;
			
			$config['base_url'] = site_url('admin/EventApplicant');
			$config['total_rows'] = $this->CsAdminEventApplicant->totalRows();
			$config['per_page'] = 20;
				
			$this->pagination->initialize($config);
			
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
	
	public function PreReserveHistory(){
		$this->_loginCheck();
		
		$data = array();
		
		$mb_id = $this->session->userdata('ss_mb_id');
		$member = $this->common->get_member($mb_id);
		$data['member'] = $member;

		$this->_header($member, $this->router->fetch_method());
		if($ea_id === ''){
			$data['etList'] = $this->CsAdminEventTeaser->getListLive();
				
			$data['et_id'] = '';
			if(isset($_REQUEST['et_id'])){
				$is_et_id = false;
			
				$data['et_id'] = (int)$_REQUEST['et_id'];
				for($i=0; $i<count($data['etList']); $i++){
					if($data['et_id'] == $data['etList'][$i]->et_id){
						$is_et_id = true;
					}
						
				}
			
				if(!$is_et_id){
					$this->common->alert('카테고리가 존재하지 않습니다.');
				}
			}
			
			if(isset($_REQUEST['page'])){
				if($_REQUEST['page']){
					$page = (int)$_REQUEST['page'];
				}else{
					$page = 1;
				}
				$board_list = $this->CsAdminEventApplicant->getList($data['et_id'], $page);
				$config['cur_page'] = $page;
			}else{
				$board_list = $this->CsAdminEventApplicant->getList($data['et_id']);
				$config['cur_page'] = 1;
			}
			
			
			/* $i=0;
			while(isset($board_list[$i])){
				$board_list[$i]->href = site_url("admin").'/'.$this->router->fetch_method().'/'.$board_list[$i]->ea_id;
				$i++;
			} */
			
			$data['blist'] = $board_list;
			
			$config['base_url'] = site_url('admin/EventApplicant');
			$config['total_rows'] = $this->CsAdminEventApplicant->totalRows();
			$config['per_page'] = 20;
				
			$this->pagination->initialize($config);
			
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
	
	public function eventTeaserAction($idx=''){
		$this->_loginCheck();
		
		$w = '';
		if($this->input->post('w', TRUE)){
			$w = $this->input->post('w', TRUE);
		}else if($this->input->get('w', TRUE)){
			$w = $this->input->get('w', TRUE);
		}
		
		if(!in_array($w, array("", "u", "d"))){
			$this->common->alert('죄송합니다. 저장오류입니다.');
			exit;
		}
		
		$data = array();
		//필수 필드 Validation [start]--------------------
		$msg = array();
		
		if($w == 'u'){
			$idx = '';
			if($this->input->post('idx', TRUE)){
				$idx = trim($this->input->post('idx', TRUE));
			}else{
				$msg[] = '죄송합니다. 저장오류입니다.';
			}
		}
		
		if($w == 'd'){
			if(!$idx){
				$msg[] = '죄송합니다. 저장오류입니다.';
			}
		}
		
		if($w == '' || $w == 'u'){
			$data['name'] = '';
			if($this->input->post('name', TRUE)){
				$data['name'] = trim($this->input->post('name', TRUE));
			}else{
				$msg[] = '<strong>이벤트 제목</strong>을 입력하세요.';
			}
			
			$data['content'] = '';
			if($this->input->post('content', TRUE)){
				$data['content'] = trim($this->input->post('content', TRUE));
			}else{
				$msg[] = '<strong>이벤트 내용</strong>을 입력하세요.';
			}
			
			$data['status'] = 0;
			if($this->input->post('status', TRUE) != ''){
				$data['status'] = trim($this->input->post('status', TRUE));
			}else{
				$msg[] = '<strong>이벤트 상태</strong>를 입력하세요.';
			}
			
			//필수 필드 Validation [end]--------------------
			$data['startDt'] = '';
			if($this->input->post('et_opendate', TRUE)){
				$data['startDt'] = trim($this->input->post('et_opendate', TRUE));
				if($this->input->post('et_openhr', TRUE)){
					$data['startDt'] .= ' '.trim($this->input->post('et_openhr', TRUE));
					if($this->input->post('et_openmin', TRUE)){
						$data['startDt'] .= ':'.trim($this->input->post('et_openmin', TRUE)).':00';
					}else{
						$data['startDt'] .= ':00:00';
					}
				}else{
					$data['startDt'] .= ' 00:00:00';
				}
			}
			
			$data['endDt'] = '';
			if($this->input->post('et_closedate', TRUE)){
				$data['endDt'] = trim($this->input->post('et_closedate', TRUE));
				if($this->input->post('et_closehr', TRUE)){
					$data['endDt'] .= ' '.trim($this->input->post('et_closehr', TRUE));
					if($this->input->post('et_closemin', TRUE)){
						$data['endDt'] .= ':'.trim($this->input->post('et_closemin', TRUE)).':00';
					}else{
						$data['endDt'] .= ':00:00';
					}
				}else{
					$data['endDt'] .= ' 00:00:00';
				}
			}
			
			$data['videoUrl'] = '';
			if($this->input->post('videoUrl', TRUE)){
				$data['videoUrl'] = trim($this->input->post('videoUrl', TRUE));
			}
			
			if($w == ''){
				$data['datetime'] = date("Y-m-d H:i:s");
			}
		}
		
		$msg = implode('<br>', $msg);
		if ($msg) {
			$this->common->alert($msg);
			exit;
		}
		
		if($w == ''){
			$this->common->print_r2($data);
			$et_id = $this->CsAdminEventTeaser->insert($data);
		}else if($w == 'u'){
			$this->CsAdminEventTeaser->update($data, $et_id);
		}else if($w == 'd'){
			$this->CsAdminEventTeaser->delete($et_id);
		}
		
		if($w == 'd'){
			redirect('admin/EventTeaser', 'refresh');
		}else{
			if($w == ''){
				redirect('admin/EventTeaser/'.$et_id, 'refresh');
			}else{
				if($this->agent->is_referral()){
					redirect($this->agent->referrer(), 'refresh');
				}else{
					redirect('admin/EventTeaser', 'refresh');
				}
			}
		}
	}
	public function EventApplicantAction(){
		$this->_loginCheck();
		
		if($this->input->post('w', TRUE)){
			$w = $this->input->post('w', TRUE);
		}else if($this->input->get('w', TRUE)){
			$w = $this->input->get('w', TRUE);
		}
		
		if(!in_array($w, array("", "u", "h"))){
			$this->common->alert('죄송합니다. 저장오류입니다.');
			exit;
		}
		
		$data = array();
		//필수 필드 Validation [start]--------------------
		$msg = array();
		$ea_id = $this->input->post('idx', TRUE);
		
		if($w == 'h'){
			if(!$ea_id){
				$msg[] = '죄송합니다. 저장오류입니다.';
			}
		}
		
		$msg = implode('<br>', $msg);
		if ($msg) {
			$this->common->alert($msg);
			exit;
		}
		
		if($w == 'h'){
			echo $flag = $this->CsAdminEventApplicant->hideUpdate($ea_id);
		}
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