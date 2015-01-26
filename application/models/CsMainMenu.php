<?php
class CsMainMenu extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from Menu')->result();
	}
	
	public function get($menu_id){
		return $this->db->get_where('Menu', array('me_id'=>$menu_id))->row();
	}
}
