<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ShareSNS extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->helper('url');
		$this->load->model('csMainMenu');
		$this->load->model('CsPreApplicant');
		$this->load->model('CsAdminEventApplicant');
	}
	
	public function index()
	{
		$flag = '';
		
		if($_REQUEST['cidx']){
			$data['cidx'] = $_REQUEST['cidx'];
			
			$row = $this->CsAdminEventApplicant->get($data['cidx']);
			if($row->type == 1)
				$flag = 'fb';
			else if($row->type == 2)
				$flag = 'tt';
			
		}else if($_REQUEST['sns']){
			$flag = $_REQUEST['sns'];
		}
		
		if($flag == 'fb')
			redirect('ShareSNS/Facebook/');
		else if($flag == 'tt')
			redirect('ShareSNS/Twitter/');
	}
	public function Facebook($cidx='')
	{
		$data['cidx'] = $cidx;
		
		$data['sns'] = '페이스북';
		$title = urlencode(str_replace('\"', '"',$this->config->item('site_title')));
		
		$str = '';
		if($cidx){
			$str = '?cidx='.$cidx;
		}
		
		$short_url = $this->common->googl_short_url(site_url('teaser'.$str));
		if(!$short_url)
			$short_url = urlencode(site_url('teaser'.$str));
		$title_url = $title.' : '.$short_url;
		
		
		/* 
		$clist = $this->CsPreApplicant->getList();
		$data['clist'] = $clist;
		 */
		header("Location:http://www.facebook.com/sharer/sharer.php?s=100&u=".$short_url."&p=".$title);
		
	}
	public function Twitter($cidx='')
	{
		$data['cidx'] = $cidx;
		
		$data['sns'] = '트위터';
		$title = urlencode(str_replace('\"', '"',$this->config->item('site_title')));
		
		$str = '';
		if($cidx){
			$str = '?cidx='.$cidx;
		}
		
		$short_url = $this->common->googl_short_url(site_url('teaser'.$str));
		if(!$short_url)
			$short_url = urlencode(site_url('teaser'.$str));
		$title_url = $title.' : '.$short_url;
		
		header("Location:http://twitter.com/home?status=".$title_url);
	}
	function _header(){
		$title = $this->config->item('site_title');
		$data = array('title' => $title);
		$this->load->view('MainHeadSub', $data);
	}
	function _footer(){
		$this->load->view('MainTailSub');
	}
}