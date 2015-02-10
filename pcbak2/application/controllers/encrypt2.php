<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Encrypt2 extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('encrypt');
	}
	
	public function index()
	{
		
		/* $msg = 'My secret message';

		$data['encrypted'] = $this->encrypt->encode($msg); 
		$this->load->view('encrypt2', $data); */
		
		//echo $this->input->post();
		//$this->common->print_r2($_POST);
		echo $this->input->post('name',true).'<br>';
		echo $this->input->post('ph2',true).'<br>';
		echo $this->input->post('ph3',true).'<br>';
	}
	
}