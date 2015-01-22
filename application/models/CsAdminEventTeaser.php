<?php
class CsAdminEventTeaser extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from cs_event_teaser order by et_datetime desc')->result();
	}
	
	public function getList($page=1){
		$start = ($page-1)*20;
		$end = $page*20;
		echo 'select * from cs_event_teaser order by et_datetime desc limit '.$start.', '.$end;
		return $this->db->query('select * from cs_event_teaser order by et_datetime desc limit '.$start.', '.$end)->result();
	}
	
	public function get($et_id){
		return $this->db->get_where('cs_event_teaser', array('et_id'=>$et_id))->row();
	}
	
	public function insert($data){
		$this->db->trans_start();
		$this->db->insert('cs_event_teaser', $data);
		$et_id = $this->db->insert_id();
		$this->db->trans_complete();
		
		return  $et_id;
	}
	
	public function update($data, $et_id){
		if(!$et_id) return;
		$this->db->where('et_id', $et_id);
		$this->db->update('cs_event_teaser', $data);
	}
	
	public function delete($et_id){
		if(!$et_id) return;
		$this->db->delete('cs_event_teaser', array('et_id' => $et_id));
		
	}
	
	public function totalRows(){
		$query = $this->db->query('SELECT * FROM cs_event_teaser order by et_datetime desc');
		return $query->num_rows();
	}
}
