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
	
	public function pRListAction() {		
		$this->output->set_content_type('application/json');
		
		$time = strtotime($this->config->item('opendate_candyshop')) - strtotime(date('Y-m-d'));
		$openDate = date('d', $time);
		$totalParticipant = $this->cs_prereserve_applicant->getLiveRows();
		$result = array('openDt' => $openDate, total => $totalParticipant, 'data' => array());		

		$mode = '';
		if($this->input->post('mode', TRUE)){
			$mode = $this->input->post('mode', TRUE);
		}
		
		if ($mode === 'afterReserve') {
			$data['idx'] = 0;
			if ($this->input->post('idx', TRUE)) {
				$data['idx'] = (int)$this->input->post('idx', TRUE);				
			}
			
			if ($this->input->post('offset', TRUE)) {
				$data['offset'] = (int)$this->input->post('offset', TRUE);
			}
			
			$clist = $this->cs_prereserve_applicant->getLineUpData($data);
		}
		
		if ($mode === 'myLocation') {
			$data['idx'] = 0;
			$data['offset'] = 5;
			if ($this->input->post('idx', TRUE)) {
				$data['idx'] = (int)$this->input->post('idx', TRUE);
			}
				
			if ($this->input->post('offset', TRUE)) {
				$data['offset'] = (int)$this->input->post('offset', TRUE);
			}

			$clist = $this->cs_prereserve_applicant->getMyLocationData($data);						
		}
		
		if ($this->input->post('size', TRUE)){
			$data['size'] = (int)$this->input->post('size', TRUE);
			$clist = $this->cs_prereserve_applicant->getData($data);
		}
						
		
// 		$data['idx'] = 0;
// 		if($this->input->post('idx', TRUE)){
// 			$data['idx'] = (int)$this->input->post('idx', TRUE);
// 		}
		
// 		$data['size'] = 5;
// 		if($this->input->post('size', TRUE)){
// 			$data['size'] = (int)$this->input->post('size', TRUE);
// 		}
		
// 		if($mode == 'last'){
// 			$data['amount'] = '';
// 			if($this->input->post('amount', TRUE)){
// 				$data['amount'] = (int)$this->input->post('amount', TRUE);
// 			}
			
// 			$cnt = (int)$this->cs_prereserve_applicant->getLiveRows();
// 			$num = ($cnt-$data['amount'])%$data['size'];
// 			$clist = $this->cs_prereserve_applicant->getListMainLast($num);
// 		}else{
// 			$clist = $this->cs_prereserve_applicant->getListMain($data);
// 		}
		
		for ($i = 0, $len = count($clist); $i < $len; $i++) {
			$chr = array(
				idx => $clist[$i]->idx
				, chrIdx => $clist[$i]->charIdx
				, name => $clist[$i]->userName
				, type => $clist[$i]->type
				, typeCss => (1 == $clist[$i]->type ? 'fb' : 'tt')
				, typeName => (1 == $clist[$i]->type ? '페이스북' : '트위터') 
				, content => $this->common->getTruncatText($clist[$i]->content, 80)
				, regDt => $this->common->getTime($clist[$i]->registDt)
			);
					
			array_push($result['data'], $chr);
		}
		
// 		$result['total'] = rand(0, 9999999);
// 		$size = rand(0, 32);
// 		$clist = array();
		
// 		for ($i = 0; $i < $size; $i++) {
// 			$clist[$i]->idx = 1;
// 			$clist[$i]->charIdx = rand(1, 6);
// 			$clist[$i]->userName = 'Nox' . rand(0, 100);
// 			$clist[$i]->type = rand(1, 2);				
// 			$clist[$i]->content = "빨리 출시해 주세요\n현기증 난단 말이예요☆현기증 난단 \n말이예요☆현기증 \n난단 말이예요☆" . rand(0, 100);
// 			$clist[$i]->registDt = date("Y-m-d H:i:s");
// 		}
				
// 		for ($i = 0, $len = count($clist); $i < $len; $i++) {
// 			$chr = array(
// 				'idx' => $clist[$i]->idx
// 				, 'chrIdx' => $clist[$i]->charIdx
// 				, 'name' => $clist[$i]->userName
// 				, 'type' => $clist[$i]->type
// 				, 'typeCss' => (1 == $clist[$i]->type ? 'fb' : 'tt')
// 				, 'typeName' => (1 == $clist[$i]->type ? '페이스북' : '트위터') 
// 				, 'content' => $this->common->getTruncatText($clist[$i]->content, 80)
// 				, 'regDt' => $this->common->getTime($clist[$i]->registDt)
// 			);
// 			array_push($result['list'], $chr);
// 		}
		
		$this->output->set_output(json_encode($result));		
	}
		
	function _header(){
		//$title = $this->config->item('site_title');
		$data = array('title' => '캔디크러쉬소다 사전예약이벤트');
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