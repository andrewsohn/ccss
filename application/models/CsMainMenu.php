<?php
class CsMainMenu extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from cs_menu')->result();
	}
	
	public function get($menu_id){
		return $this->db->get_where('cs_menu', array('me_id'=>$menu_id))->row();
	}
}
