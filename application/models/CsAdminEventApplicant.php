<?php
class CsAdminEventApplicant extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from cs_event_applicant')->result();
	}
	
	public function get($ea_id){
		return $this->db->get_where('cs_event_applicant', array('ea_id'=>$ea_id))->row();
	}
}
