<?php
class CsPreApplicant extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function gets(){
		return $this->db->query('select * from EventsNotices')->result();
	}
	
	public function getList($et_id='', $page=1){
		$start = ($page-1)*20;
		$end = $page*20;
		$wc = '';
		if($et_id){
			$wc = 'where a.idx = '.$et_id;
		}
		return $this->db->query('select a.*, b.name as event_name, c.name as code_name from EventsNotices a 
				left join Events b on b.idx=a.eventIdx 
				left join Codes c on c.gid=3 and c.id=a.type '.$wc.' order by registDt desc limit '.$start.', '.$end)->result();
	}
	
	public function get($ea_id){
		if(!$ea_id) return;
		return $this->db->get_where('EventsNotices', array('ea_id'=>$ea_id))->row();
	}
	
	public function hideUpdate($ea_id){
		if(!$ea_id) return;
		$row = $this->db->get_where('EventsNotices', array('idx'=>$ea_id))->row();
		$this->db->where('idx', $ea_id);
		$flag = 'N';
		if($row->visible == 'N'){
			$flag = 'Y';
			$this->db->update('EventsNotices', array('visible' => $flag));
		}else{
			$this->db->update('EventsNotices', array('visible' => $flag));
		}
		
		if(!$this->db->affected_rows()) return;
		return $flag;
	}
	
	public function totalRows(){
		$query = $this->db->query('SELECT * FROM EventsNotices order by registDt desc');
		return $query->num_rows();
	}
}
