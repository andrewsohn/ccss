<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Encrypt extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('encrypt');
	}
	
	public function index()
	{
		
		/* $msg = 'My secret message';

		$data['encrypted'] = $this->encrypt->encode($msg); */

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->load->view('encrypt');
		
	}
	
	public function getEncrypt()
	{
		$arr = array();
		$arr['name'] = '';
		if($this->input->post('name', TRUE)){
			$arr['name'] = $this->encrypt->encode(trim($this->input->post('name', TRUE)));
		}
		$arr['ph2'] = '';
		if($this->input->post('ph2', TRUE)){
			$arr['ph2'] = $this->encrypt->encode(trim($this->input->post('ph2', TRUE)));
		}
		$arr['ph3'] = '';
		if($this->input->post('ph3', TRUE)){
			$arr['ph3'] = $this->encrypt->encode(trim($this->input->post('ph3', TRUE)));
		}
		
		echo json_encode($arr);
	}
}