<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teaser extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->helper('url');
		$this->load->model('csMainMenu');
		$this->load->model('CsAdminEventTeaser');
		$this->load->model('CsAdminEventApplicant');
		$this->load->model('CsSns');
	}
	
	public function index()
	{
		$data = $this->session->all_userdata();
		$this->_header();
		
		$view = '';
		if(isset($_REQUEST['et_id'])){
			if($_REQUEST['et_id']){
				$view = $this->CsAdminEventTeaser->get($_REQUEST['et_id']);
			}
		}
		
		if(empty($view))
			$view = $this->CsAdminEventTeaser->getLastLive();
		
		$data['view'] = $view;
		if(!empty($view)){
			$clist = $this->CsAdminEventApplicant->getListMain($view->idx);
			$data['clist'] = $clist;
		}
			
		
		$board_list = $this->CsAdminEventTeaser->getListLive();
		
		$data['blist'] = $board_list;
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->view('teaser', $data);
		
		$this->_footer();
	}
	
	public function applyAction()
	{
		if($this->input->post('sns', TRUE)){
			$idx = $this->input->post('sns', TRUE);
			$sns = $this->CsSns->get($idx);
			$this->output->set_header('Content-Type: application/json; charset=utf-8');
			echo json_encode($sns);
		}
	}
	
	public function getMoreList()
	{
		if($this->input->post('idx', TRUE) && $this->input->post('idx2', TRUE)){
			$idx = $this->input->post('idx', TRUE);
			$idx2 = $this->input->post('idx2', TRUE);
			//echo json_encode($idx.':'.$idx2);
			$clist = $this->CsAdminEventApplicant->getListMore($idx,$idx2);
			$this->output->set_header('Content-Type: application/json; charset=utf-8');
			echo json_encode($clist);
		}
	}
	
	function _header(){
		$title = $this->config->item('site_title');
		$data = array('title' => $title);
		$menu_list = $this->csMainMenu->gets();
		$this->load->view('MainHeadSub', $data);
		$this->load->view('MainHead');
	}
	function _footer(){
		$this->load->view('MainTail');
		$this->load->view('MainTailSub');
	}
}