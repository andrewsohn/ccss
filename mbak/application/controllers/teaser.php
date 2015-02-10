<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teaser extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->helper('url');
		$this->load->model('cs_main_menu');
		$this->load->model('cs_admin_event_teaser');
		$this->load->model('cs_admin_event_applicant');
		$this->load->model('cs_prereserve_applicant');
		$this->load->model('cs_sns');
	}
	
	public function index()
	{
		$data = $this->session->all_userdata();
		$this->_header();
		
		$view = '';
		if(isset($_REQUEST['et_id'])){
			if($_REQUEST['et_id']){
				$view = $this->cs_admin_event_teaser->get($_REQUEST['et_id']);
			}
		}
		
		if(empty($view))
			$view = $this->cs_admin_event_teaser->getLastLive();
		
		$data['view'] = $view;
		if(!empty($view)){
			$clist = $this->cs_admin_event_applicant->getListMainMob($view->idx);
			$data['clist'] = $clist;
		}
		//echo $view->idx;
		//$this->common->print_r2($clist);
		
		$board_list = $this->cs_admin_event_teaser->getListLive();
		
		$data['blist'] = $board_list;
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->view('MobileTeaser', $data);
		
		$this->_footer();
	}
	
	public function applyAction()
	{
		if($this->input->post('sns', TRUE)){
			$idx = $this->input->post('sns', TRUE);
			$sns = $this->cs_sns->get($idx);
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
			$clist = $this->cs_admin_event_applicant->getListMore($idx,$idx2);
			//$this->output->set_header('Content-Type: application/json; charset=utf-8');
			$str = '';
			for($i=0; $i<count($clist); $i++){
				$str .= '<li>';
				
				$img_path = $this->config->item('asset_url').'/PC/img/@thumb/thumb.jpg';
				$filename = $clist[$i]->idx.'_thumb.'.$this->common->getValueByCode('20',$clist[$i]->photoType);
				$filepath = "http://2j5xlt4h84.ecn.cdn.infralab.net/data/event/".str_replace("-","",substr($clist[$i]->registDt,0,10)).'/'.$filename;
					
				$imgarr = getimagesize($filepath);
					
				$wh = 'width';
				if(is_array($imgarr)){
					$img_path = $filepath;
					if($imgarr[0]>$imgarr[1])
						$wh = 'height';
				}
				
				$ahref = '#';
				if($clist[$i]->type == 1){
					$ahref = 'https://www.facebook.com/app_scoped_user_id/'.$clist[$i]->userId.'/';
				}else if($clist[$i]->type == 2){
					$ahref = 'https://twitter.com/intent/user?user_id='.$clist[$i]->userId;
				}
				$str .= '<a href="'.$ahref.'" target="_blank">';
				$str .= '<span class="tmb"><img src="'.$img_path.'" style="'.$wh.':100%" alt=""></span>';
				$str .= '<div class="txt">';
				$str .= '<span class="tmb"><img src="'.$clist[$i]->photoUrl.'" style="width:100%" alt="'.$clist[$i]->userName.'프로필사진"></span>';
				$str .= '<em>'.$clist[$i]->userName.'</em>'.$this->common->getTime($clist[$i]->registDt);
				$str .= '<p>'.$this->common->getShortenText($clist[$i]->content).'</p>';
				$str .= '</div>';
				$str .= '</a>';
				$str .= '<button class="';
				if($clist[$i]->type == 1) $str .= 'btn_fb'; else $str .= 'btn_fb';
				$str .= '"><span>';
				if($clist[$i]->type == 1) $str .= '페이스북'; else $str .= '트위터';
				$str .= ' 공유</span></button></li>';
				if($i == count($clist)-1)
					$str .= '|||'.$clist[$i]->idx;
			}
			
			echo $str;
		}
	}
	
	function _header(){
		$title = $this->config->item('site_title');
		$data = array('title' => $title);
		$menu_list = $this->cs_main_menu->gets();
		$this->load->view('MobileHeadSub', $data);
		$data2['menu'] = $this->cs_main_menu->getsLive();
		$this->load->view('MobileHead',$data2);
	}
	function _footer(){
		$this->load->view('MobileTail');
		$this->load->view('MobileTailSub');
	}
}