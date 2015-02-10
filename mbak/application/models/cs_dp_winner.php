<?php
class Cs_dp_winner extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('universaluid');
		$this->load->library('common');
	}
	
	public function gets(){
		return $this->db->query('select * from DailyPrizeWinners')->result();
	}
	
	public function get($idx){
		if(!$ea_id) return;
		return $this->db->get_where('DailyPrizeWinners', array('idx'=>$idx))->row();
	}
	
	public function insert($prmGoodsIdx=''){
		if(!$prmGoodsIdx) return;
		
		$data['dt'] = date('Y-m-d');
		$data['prmGoodsIdx'] = $prmGoodsIdx;
		$data['amount'] = 1;
		$query = $this->db->get_where('DailyPrizeWinners', array('prmGoodsIdx'=>$prmGoodsIdx,'dt'=>$data['dt']));
		if ($query->num_rows() > 0){
			$row = $query->row();
			$data['amount'] = (int)$row->amount + 1;
			$this->db->update('DailyPrizeWinners', $data, array('prmGoodsIdx' => $prmGoodsIdx, 'dt'=>$data['dt']));
		}else{
			$this->db->insert('DailyPrizeWinners', $data);
		}
	}
}
