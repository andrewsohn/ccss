<?php
class CsPreReserveApplicant extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('universaluid');
		$this->load->library('common');
	}
	
	public function gets(){
		return $this->db->query('select * from Reservations')->result();
	}
	
	public function getList($page=1){
		$start = ($page-1)*20;
		$end = $page*20;
		
		$sql = "select a.*, c.name as code_name from Reservations a
				left join Codes c on c.gid=3 and c.id=a.type ";
		$arr = array();
		$sql .= ' order by a.registDt desc limit ? , ?';
		array_push($arr,$start,$end);
		
		return $this->db->query($sql, $arr)->result();
	}
	
	public function getListMain($data=array()){
		$sql = "select * from Reservations 
				where status = 1 and idx > ? order by idx asc limit ?";
		
		
		return $this->db->query($sql, $data)->result();
	}
	
	public function getListMainLast($num=0){
		$arr = array();
		$sql = "SELECT a.* FROM (
		SELECT * FROM Reservations ORDER BY idx DESC LIMIT ?
		) a
		ORDER BY idx ASC";
		array_push($arr,$num);
		return $this->db->query($sql, $arr)->result();
	}
	
	public function get($ea_id){
		if(!$ea_id) return;
		return $this->db->get_where('Reservations', array('idx'=>$ea_id))->row();
	}
	
	public function insertApply($data=array()){
		if(empty($data)) return;
		$data['idx'] = $this->universaluid->v4();
		$data['photoType'] = $this->common->getIdByName('20',$data['photoType']);
		
		$this->db->trans_start();
		$this->db->insert('Reservations', $data);
		$this->db->trans_complete();
	
		return  $data['idx'];
	}
	
	public function hideUpdate($ea_id){
		if(!$ea_id) return;
		$row = $this->db->get_where('Reservations', array('idx'=>$ea_id))->row();
		$this->db->where('idx', $ea_id);
		$flag = $row->status;
		if($flag == 0){
			$flag = 1;
			$this->db->update('Reservations', array('status' => $flag));
		}else{
			$flag = 0;
			$this->db->update('Reservations', array('status' => $flag));
		}
		
		$flag = $flag == 0? '보이기':'숨기기';
		if(!$this->db->affected_rows()) return;
		return $flag;
	}
	
	public function totalRows(){
		$query = $this->db->query('SELECT * FROM Reservations order by idx asc');
		return $query->num_rows();
	}
	
	public function delete($data=array()){
		if(empty($data)) return;
		$idx = $data['uuid'];
		$this->db->delete('Reservations', array('idx' => $idx));
	}
}
