<?php
class CsAdminEventTeaser extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from cs_event_teaser')->result();
	}
	
	public function get($et_id){
		return $this->db->get_where('cs_event_teaser', array('et_id'=>$et_id))->row();
	}
}
