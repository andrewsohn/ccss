<?php
class Cs_prereserve_applicant extends CI_Model{
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
	
	public function getMain($idx=''){
		if(!$idx) return;
		$this->db->select('a.*, b.name as userName');
		$this->db->from('Reservations a');
		$this->db->join('Users b', 'a.userId = b.id and a.userType = b.type', 'left');
		$this->db->where('a.status', 1);
		$w = 'a.idx = (select min(idx) from Reservations where idx > '.$idx.' AND userId is not null )';
		$this->db->where($w);
		$this->db->order_by("a.registDt", "asc");
		$this->db->limit(1);
		return $this->db->get()->row();
	}
	
	public function getPrevIdx($data=array()){
		if(empty($data)) return;
		$this->db->select('idx');
		$this->db->from('Reservations');
		$this->db->where('status', 1);
		$w = 'idx = (select DISTINCT idx from Reservations where idx < '.$data['idx'].' AND userId is not null order by idx desc limit '.$data['size'].',1)';
		$this->db->where($w);
		$this->db->order_by("registDt", "asc");
		$this->db->limit(1);
		return $this->db->get()->row()->idx;
	}
	
	public function getListMain($data=array()){
		$this->db->select('a.*, b.name as userName');
		$this->db->from('Reservations a');
		$this->db->join('Users b', 'a.userId = b.id and a.userType = b.type', 'left');
		$this->db->where('a.status', 1);
		$this->db->where('a.userId is not null');
		$w = 'a.idx > '.$data['idx'];
		$this->db->where($w);
		$this->db->order_by("a.registDt", "asc");
		$this->db->limit($data['size']);
		
		return $this->db->get()->result();
	}
	
	
	public function getData($data=array()){
		$this->db->select('a.*, b.name as userName');
		$this->db->from('Reservations a');
		$this->db->join('Users b', 'a.userId = b.id and a.userType = b.type', 'left');
		$this->db->where('a.status', 1);
		$this->db->where('a.userId is not null');				
		$this->db->order_by("a.registDt", "asc");
		$this->db->limit($data['size']);
	
		return $this->db->get()->result();
	}
	
	public function getLineUpData($data=array()) {
		$sql = "
			SELECT * FROM (
				SELECT a.idx, a.userId, b.name AS userName, a.charIdx
				, a.type, a.registDt, a.content
				FROM (`Reservations` a)
				INNER JOIN `Users` b ON `a`.`userId` = `b`.`id` and a.userType = b.type
				WHERE `a`.`status` =  1
				AND `a`.`userId` IS NOT NULL
				AND `a`.`idx` <= ?
				ORDER BY a.idx DESC
				LIMIT ?
			) AS A 			 
			ORDER BY idx ASC
		";
		
		return $this->db->query($sql, array($data['idx'], $data['offset']))->result();
	}
	
	public function getMyLocationData($data=array()) {
		$sql = "
			SELECT * FROM (
				SELECT `a`.idx, a.userId, b.name AS userName, a.charIdx, a.type, a.registDt, a.content
				, 1 as utype
				FROM (`Reservations` a)
				INNER JOIN `Users` b ON `a`.`userId` = `b`.`id` and a.userType = b.type
				WHERE `a`.`status` =  1
				AND `a`.`userId` IS NOT NULL
				AND `a`.`idx` <= ?
				ORDER BY a.idx DESC
				LIMIT ?
			) AS A 
			UNION 
			SELECT * FROM (
				SELECT `a`.idx, a.userId, b.name AS userName, a.charIdx, a.type, a.registDt, a.content
				, 2 as utype
				FROM (`Reservations` a)
				INNER JOIN `Users` b ON `a`.`userId` = `b`.`id` and a.userType = b.type
				WHERE `a`.`status` =  1
				AND `a`.`userId` IS NOT NULL
				AND `a`.`idx` > ?
				ORDER BY a.idx DESC
				LIMIT ?
			) AS A 
			ORDER BY idx ASC				
		";
	
		return $this->db->query($sql, array($data['idx'], $data['offset'], $data['idx'], $data['offset']))->result();
	}
	
	public function getListMainLast($num=0){
		$this->db->select('*');
		$this->db->from('(select a.*, b.name as userName from Reservations a left join Users b on a.userId = b.id and a.userType = b.type where a.status = 1 and a.userId is not null order by a.registDt desc limit '.$num.') as c');
		$this->db->order_by("c.registDt", "asc");
		return $this->db->get()->result();
	}
	
	public function getListMobLive(){
		$this->db->select('a.*, b.name as vname');
		$this->db->from('Reservations a');
		$this->db->join('Users b', 'a.userId = b.id and a.userType = b.type', 'left');
		$this->db->where('a.status', 1);
		$this->db->where('a.userId is not null');
		$this->db->order_by("a.registDt", "desc");
		$this->db->limit(7);
		return $this->db->get()->result();
	}
	
	public function getListMoreMob($idx=''){
		if(!$idx) return;
		$this->db->select('a.*, b.name as vname');
		$this->db->from('Reservations a');
		$this->db->join('Users b', 'a.userId = b.id and a.userType = b.type', 'left');
		$this->db->where('a.status', 1);
		$this->db->where('a.userId is not null');
		$sql = 'a.registDt < (select registDt from Reservations where idx = '.$idx.')';
		$this->db->where($sql);
		$this->db->order_by("a.registDt", "desc");
		$this->db->limit(7);
		
		return $this->db->get()->result();
	}
	
	public function get($ea_id){
		if(!$ea_id) return;
		return $this->db->get_where('Reservations', array('idx'=>$ea_id))->row();
	}
	
	public function getLastLiveIdxByUser($user_id='', $snsType=''){
		if(!$user_id && !$snsType) return;
		$this->db->select('idx');
		$this->db->from('Reservations');
		$this->db->where('status', 1);
		$this->db->where('userId', $user_id);
		$this->db->where('type', $snsType);
		$this->db->order_by("idx", "desc");
		$this->db->limit(1);
		return $this->db->get()->row()->idx;
	}
	
	public function insertApply($data=array()){
		if(empty($data)) return;
		$this->db->trans_start();
		$this->db->insert('Reservations', $data);
		$idx = $this->db->insert_id();
		$this->db->trans_complete();
	
		return  $idx;
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
	
	public function getLiveRows(){
		$this->db->select('*');
		$this->db->from('Reservations');
		$this->db->where('status', 1);
		$this->db->where('userId is not null');
		$this->db->order_by("registDt", "asc");
		return $this->db->get()->num_rows();
	}
	
	public function delete($data=array()){
		if(empty($data)) return;
		$idx = $data['uuid'];
		$this->db->delete('Reservations', array('idx' => $idx));
	}
	
	//true=중복, false=통과
	public function checkNamePhone($data=array()){
		$res = false;
		if(empty($data)) return true;
		
		$this->db->where(array('userName' => $data['userName'], 'mobileNum'=> $data['mobileNum']));
		$query = $this->db->get('Reservations');
		if ($query->num_rows() > 0){
			$res = true;
		}
		return $res;
	}
	
	//true=중복, false=통과
	public function checkPhone($data=array()){
		$res = false;
		if(empty($data)) return true;
		
		$this->db->where(array('mobileNum'=> $data['mobileNum']));
		$query = $this->db->get('Reservations');
		if ($query->num_rows() > 0){
			$res = true;
		}
		return $res;
	}
}
