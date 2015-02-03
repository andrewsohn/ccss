<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PreReserve extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->helper('url');
		$this->load->model('csMainMenu');
		$this->load->model('CsPreReserveApplicant');
		$this->load->model('CsSns');
	}
	
	public function index()
	{
		$data = $this->session->all_userdata();
		$this->_header();
		
		
		//$clist = $this->CsPreReserveApplicant->getListMain($vmode,$idx);
		//$data['clist'] = $clist;
		
		/* $data['sidx'] = '';
		if($clist[0]->idx)
			$data['sidx'] = $clist[0]->idx;  */
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->load->view('preReserve', $data);
		
		$this->_footer();
	}
	public function pRAction(){
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
			$cnt = (int)$this->CsPreReserveApplicant->totalRows();
			$num = ($cnt-5)%$data['size'];
			$clist = $this->CsPreReserveApplicant->getListMainLast($num);
		}else{
			$clist = $this->CsPreReserveApplicant->getListMain($data);
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