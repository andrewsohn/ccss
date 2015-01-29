<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ShareSNS extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->helper('url');
		$this->load->model('csMainMenu');
		$this->load->model('CsPreApplicant');
		$this->load->model('CsSns');
	}
	
	public function index()
	{
		if(!isset($_REQUEST['cidx'])) return;
		if($_REQUEST['cidx']){
			$data['cidx'] = (int)$_REQUEST['cidx'];
		}
		$this->_header();
		$this->load->view('ShareSNS', $data);
		$this->_footer();
	}
	public function Facebook($cidx='')
	{
		if(!$cidx) return;
		$data['cidx'] = $cidx;
		
		$data['sns'] = '페이스북';
		$title = urlencode(str_replace('\"', '"',$this->config->item('site_title')));
		
		$short_url = $this->common->googl_short_url(site_url('teaser?cidx='.$cidx));
		if(!$short_url)
			$short_url = urlencode(site_url('teaser?cidx='.$cidx));
		$title_url = $title.' : '.$short_url;
		
		
		/* 
		$clist = $this->CsPreApplicant->getList();
		$data['clist'] = $clist;
		 */
		header("Location:http://www.facebook.com/sharer/sharer.php?s=100&u=".$short_url."&p=".$title);
		
	}
	public function Twitter($cidx='')
	{
		if(!$cidx) return;
		$data['cidx'] = $cidx;
		
		$data['sns'] = '트위터';
		$title = urlencode(str_replace('\"', '"',$this->config->item('site_title')));
		
		$short_url = $this->common->googl_short_url(site_url('teaser?cidx='.$cidx));
		if(!$short_url)
			$short_url = urlencode(site_url('teaser?cidx='.$cidx));
		$title_url = $title.' : '.$short_url;
		
		/* 
		$clist = $this->CsPreApplicant->getList();
		$data['clist'] = $clist;
		 */
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