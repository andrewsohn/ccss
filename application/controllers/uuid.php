<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uuid extends CI_Controller {
	function __construct(){
		parent::__construct();
		// if(class_exists('uuid') != true) {
			$this->load->library('universaluid');
// 		}
		
	}
	
	public function index()
	{
		$data = $this->session->all_userdata();
		//Output a v4 UUID
		echo $this->universaluid->v4();
		
		
		
// 		echo 'dddd';
		
		/* 
		$clist = $this->CsPreApplicant->getList();
		$data['clist'] = $clist;
		 */
		
		//$this->load->view('uuid', $data);
		
	}
}