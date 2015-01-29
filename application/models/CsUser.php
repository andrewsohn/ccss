<?php
class CsUser extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from Users')->result();
	}
	
	public function get($id){
		if(!$id) return;
		return $this->db->get_where('Users', array('id'=>$id))->row();
	}
	
	public function checkNSave($user_arr=array()){
		if(empty($user_arr)) return;
		$user = array();
		$row = $this->db->get_where('Users', array('id'=>$user_arr['id'],'type'=>$user_arr['type']))->row();
		if(!count($row)){
			$user_arr['registDt'] = date("Y-m-d H:i:s");
			$this->db->trans_start();
			$this->db->insert('Users', $user_arr);
			if($this->db->affected_rows() != 1)
				$user = $user_arr;
			$this->db->trans_complete();
		}else{
			$user = $row;
		}
		
		return  $user;
	}
}
