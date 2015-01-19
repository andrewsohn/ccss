<?php
class CsAdminMenu extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from cs_admin_menu')->result();
	}
	
	public function get($menu_id){
		return $this->db->get_where('cs_admin_menu', array('am_id'=>$menu_id))->row();
	}
}
