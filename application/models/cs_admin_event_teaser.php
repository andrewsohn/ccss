<?php
class Cs_admin_event_teaser extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from Events order by registDt desc')->result();
	}
	
	public function getList($page=1){
		$start = ($page-1)*20;
		$end = $page*20;
		return $this->db->query('select * from Events order by registDt desc limit '.$start.', '.$end)->result();
	}
	
	public function getListLive(){
		
		return $this->db->query('select * from Events where status = 1 order by registDt desc')->result();
	}
	
	public function get($et_id){
		return $this->db->get_where('Events', array('idx'=>$et_id))->row();
	}
	
	public function getLastLive(){
		return $this->db->query('select * from Events where status = 1 order by registDt desc')->first_row();
	}
	
	public function insert($data){
		$this->db->trans_start();
		$this->db->insert('Events', $data);
		$et_id = $this->db->insert_id();
		$this->db->trans_complete();
		
		return  $et_id;
	}
	
	public function update($data, $et_id){
		if(!$et_id) return;
		$this->db->where('idx', $et_id);
		$this->db->update('Events', $data);
	}
	
	public function delete($et_id){
		if(!$et_id) return;
		$this->db->delete('Events', array('idx' => $et_id));
		
	}
	
	public function totalRows(){
		$query = $this->db->query('SELECT * FROM Events order by registDt desc');
		return $query->num_rows();
	}
}
