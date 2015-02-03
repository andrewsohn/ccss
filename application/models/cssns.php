<?php
class Cssns extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from Sns')->result();
	}
	
	public function get($idx){
		return $this->db->get_where('Sns', array('idx'=>$idx))->row();
	}
}
