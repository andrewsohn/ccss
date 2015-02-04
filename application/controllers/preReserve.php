<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PreReserve extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('encrypt');
		$this->load->helper('url');
		$this->load->model('csMainMenu');
		$this->load->model('cs_prereserve_applicant');
		$this->load->model('CsSns');
	}
	
	public function index()
	{
		$data = $this->session->all_userdata();
		$this->_header();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$data['client_num'] = $this->cs_prereserve_applicant->getLiveRows();
		
		$this->load->view('preReserve', $data);
		
		$this->_footer();
	}
	public function getEncrypted()
	{
		$arr = array();
		$arr['name'] = '';
		if($this->input->post('name', TRUE)){
			$arr['name'] = $this->encrypt->encode(trim($this->input->post('name', TRUE)));
		}
		$arr['phNum1'] = '';
		if($this->input->post('phNum1', TRUE)){
			$arr['phNum1'] = $this->encrypt->encode(trim($this->input->post('phNum1', TRUE)));
		}
		$arr['phNum2'] = '';
		if($this->input->post('phNum2', TRUE)){
			$arr['phNum2'] = $this->encrypt->encode(trim($this->input->post('phNum2', TRUE)));
		}
		$arr['phNum3'] = '';
		if($this->input->post('phNum3', TRUE)){
			$arr['phNum3'] = $this->encrypt->encode(trim($this->input->post('phNum3', TRUE)));
		}
	
		echo json_encode($arr);
	}
	
	public function pRListAction(){
		$mode = '';
		if($this->input->post('mode', TRUE)){
			$mode = $this->input->post('mode', TRUE);
		}
		
		$data['idx'] = 0;
		if($this->input->post('idx', TRUE)){
			$data['idx'] = (int)$this->input->post('idx', TRUE);
		}
		
		$data['size'] = 5;
		if($this->input->post('size', TRUE)){
			$data['size'] = (int)$this->input->post('size', TRUE);
		}
		
		if($mode == 'last'){
			$cnt = (int)$this->cs_prereserve_applicant->totalRows();
			$num = ($cnt-5)%$data['size'];
			$clist = $this->cs_prereserve_applicant->getListMainLast($num);
		}else{
			$clist = $this->cs_prereserve_applicant->getListMain($data);
		}
		
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$str = '';
		for($i=0; $i<count($clist); $i++){
			$str .= '<li>';
			$str .= '<dl>';
			$str .= '<dt>'.$clist[$i]->idx.'</dt>';
			$str .= '<dd>'.$clist[$i]->content.'</dd>';
			$str .= '<dd>'.$clist[$i]->userId.'</dd>';
			$str .= '<dd>'.$clist[$i]->userType.'</dd>';
			$str .= '<dd>'.$clist[$i]->charIdx.'</dd>';
			$str .= '</dl></li>';
			if($i == count($clist)-1)
				$str .= '|||'.$clist[$i]->idx;
		}
		echo json_encode($str);
		
	}
	
	function _header(){
		$title = $this->config->item('site_title');
		$data = array('title' => $title);
		$menu_list = $this->csMainMenu->gets();
		$this->load->view('MainHeadSub', $data);
		$data2['menu'] = $this->csMainMenu->getsLive();
		$this->load->view('MainHead',$data2);
	}
	function _footer(){
		$this->load->view('MainTail');
		$this->load->view('MainTailSub');
	}
}