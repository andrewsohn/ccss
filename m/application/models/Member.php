<?php
class Member extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from AdminMember')->result();
	}
	
	public function get($mb_id){
		return $this->db->get_where('AdminMember', array('mb_id'=>$mb_id))->row();
	}
}
