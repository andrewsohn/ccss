<?php
class Cs_main_menu extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from Menu')->result();
	}
	
	public function getsLive(){
		return $this->db->query('select * from Menu where me_use = 1 order by me_order asc')->result();
	}
	
	public function get($menu_id){
		return $this->db->get_where('Menu', array('me_id'=>$menu_id))->row();
	}
}
