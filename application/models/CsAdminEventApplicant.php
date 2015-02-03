<?php
class CsAdminEventApplicant extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('universaluid');
		$this->load->library('common');
	}
	
	public function gets(){
		return $this->db->query('select * from EventsNotices')->result();
	}
	
	public function getList($et_id='', $page=1){
		$start = ($page-1)*20;
		$end = $page*20;
		
		$sql = "select a.*, b.title as event_name, c.name as code_name from EventsNotices a
				left join Events b on b.idx=a.eventIdx
				left join Codes c on c.gid=3 and c.id=a.type ";
		$arr = array();
		if($et_id){
			$sql .= 'where b.idx = ?';
			array_push($arr,$et_id);
		}
		$sql .= ' order by a.registDt desc limit ? , ?';
		array_push($arr,$start,$end);
		
		return $this->db->query($sql, $arr)->result();
	}
	
	public function getListMain($et_id='', $page=1){
		$start = ($page-1)*6;
		$end = $page*6;
		
		$sql = "select a.*, b.title as event_name, c.name as code_name, d.name as userName, d.type as userSNStype, d.photoUrl 
				from EventsNotices a
				left join Events b on b.idx=a.eventIdx
				left join Codes c on c.gid=3 and c.id=a.type
				left join Users d on d.id = a.userId
				where a.status=1 and a.refIdx IS NULL OR a.refIdx = '' ";
		$arr = array();
		if($et_id){
			$sql .= 'and b.idx = ?';
			array_push($arr,$et_id);
		}
		$sql .= ' order by a.registDt desc limit ? , ?';
		array_push($arr,$start,$end);
		
		return $this->db->query($sql, $arr)->result();
	}
	
	public function getListMainMob($et_id='', $page=1){
		$start = ($page-1)*4;
		$end = $page*4;
		
		$sql = "select a.*, b.title as event_name, c.name as code_name, d.name as userName, d.type as userSNStype, d.photoUrl 
				from EventsNotices a
				left join Events b on b.idx=a.eventIdx
				left join Codes c on c.gid=3 and c.id=a.type
				left join Users d on d.id = a.userId
				where a.status=1 and a.refIdx IS NULL OR a.refIdx = '' ";
		$arr = array();
		if($et_id){
			$sql .= 'and b.idx = ?';
			array_push($arr,$et_id);
		}
		$sql .= ' order by a.registDt desc limit ? , ?';
		array_push($arr,$start,$end);
		
		return $this->db->query($sql, $arr)->result();
	}
	
	public function getListMore($et_id='', $idx=''){
		$sql = "select a.*, b.title as event_name, c.name as code_name, d.name as userName, d.type as userSNStype, d.photoUrl 
				from EventsNotices a
				left join Events b on b.idx=a.eventIdx
				left join Codes c on c.gid=3 and c.id=a.type
				left join Users d on d.id = a.userId
				where a.status=1 and a.refIdx IS NULL OR a.refIdx = '' and a.registDt < (select registDt from EventsNotices where idx = ?) ";
		$arr = array();
		array_push($arr,$idx);
		if($et_id){
			$sql .= 'and b.idx = ?';
			array_push($arr,$et_id);
		}
		$sql .= ' order by a.registDt desc limit ? , ?';
		array_push($arr,0,6);
		
		return $this->db->query($sql, $arr)->result();
	}
	
	public function getListMoreMob($et_id='', $idx=''){
		$sql = "select a.*, b.title as event_name, c.name as code_name, d.name as userName, d.type as userSNStype, d.photoUrl 
				from EventsNotices a
				left join Events b on b.idx=a.eventIdx
				left join Codes c on c.gid=3 and c.id=a.type
				left join Users d on d.id = a.userId
				where a.status=1 and a.refIdx IS NULL OR a.refIdx = '' and a.registDt < (select registDt from EventsNotices where idx = ?) ";
		$arr = array();
		array_push($arr,$idx);
		if($et_id){
			$sql .= 'and b.idx = ?';
			array_push($arr,$et_id);
		}
		$sql .= ' order by a.registDt desc limit ? , ?';
		array_push($arr,0,4);
		
		return $this->db->query($sql, $arr)->result();
	}
	
	public function get($ea_id){
		if(!$ea_id) return;
		return $this->db->get_where('EventsNotices', array('idx'=>$ea_id))->row();
	}
	
	public function insertApply($data=array()){
		if(empty($data)) return;
		$data['idx'] = $this->universaluid->v4();
		$data['photoType'] = $this->common->getIdByName('20',$data['photoType']);
		
		$this->db->trans_start();
		$this->db->insert('EventsNotices', $data);
		$this->db->trans_complete();
	
		return  $data['idx'];
	}
	
	public function hideUpdate($ea_id){
		if(!$ea_id) return;
		$row = $this->db->get_where('EventsNotices', array('idx'=>$ea_id))->row();
		$this->db->where('idx', $ea_id);
		$flag = $row->status;
		if($flag == 0){
			$flag = 1;
			$this->db->update('EventsNotices', array('status' => $flag));
		}else{
			$flag = 0;
			$this->db->update('EventsNotices', array('status' => $flag));
		}
		
		$flag = $flag == 0? '보이기':'숨기기';
		if(!$this->db->affected_rows()) return;
		return $flag;
	}
	
	public function totalRows(){
		$query = $this->db->query('SELECT * FROM EventsNotices order by registDt desc');
		return $query->num_rows();
	}
	
	public function delete($data=array()){
		if(empty($data)) return;
		$idx = $data['uuid'];
		$this->db->delete('EventsNotices', array('idx' => $idx));
	}
}
