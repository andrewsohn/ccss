<?php
class CsAdminMenu extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from AdminMenu order by am_menu asc, am_code asc')->result();
	}
	
	public function get($menu_id){
		return $this->db->get_where('AdminMenu', array('am_id'=>$menu_id))->row();
	}
}
