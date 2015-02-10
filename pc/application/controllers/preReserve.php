<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PreReserve extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('encrypt');
		$this->load->helper('url');
		$this->load->model('cs_main_menu');
		$this->load->model('cs_prereserve_applicant');
		$this->load->model('cs_sns');
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
			$data['amount'] = '';
			if($this->input->post('amount', TRUE)){
				$data['amount'] = (int)$this->input->post('amount', TRUE);
			}
			
			$cnt = (int)$this->cs_prereserve_applicant->getLiveRows();
			$num = ($cnt-$data['amount'])%$data['size'];
			$clist = $this->cs_prereserve_applicant->getListMainLast($num);
		}else{
			$clist = $this->cs_prereserve_applicant->getListMain($data);
		}
		
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$list = array();
		for ($i = 0, $len = count($clist); $i < $len; $i++) {
			$chr = array(
				'idx' => $clist[$i]->idx
				, 'chrIdx' => $clist[$i]->charIdx
				, 'name' => $clist[$i]->userName
				, 'type' => $clist[$i]->type
				, 'typeName' => $this->common->getValueByCode(3, $clist[$i]->type)
				, 'content' => $clist[$i]->content
				, 'regDt' => $this->common->getTime($clist[$i]->registDt)
			);
			array_push($list, $chr);
		}
		
		echo json_encode($list);		
	}
		
	function _header(){
		$title = $this->config->item('site_title');
		$data = array('title' => $title);
		$menu_list = $this->cs_main_menu->gets();
		$this->load->view('MainHeadSub', $data);
		$data2['menu'] = $this->cs_main_menu->getsLive();
		$this->load->view('MainHead',$data2);
	}
	function _footer(){
		$this->load->view('MainTail');
		$this->load->view('MainTailSub');
	}
}