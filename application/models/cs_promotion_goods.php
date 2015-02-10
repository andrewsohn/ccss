<?php
class Cs_promotion_goods extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('universaluid');
		$this->load->library('common');
	}
	
	public function gets(){
		return $this->db->query('select * from EventsNotices')->result();
	}
	
	public function getList(){
		/* select a.*, b.name, c.total_amount, d.amount as today_amount
from PromotionGoods a
left join Goods b
on a.goodsIdx = b.idx
left join (select prmGoodsIdx, SUM(amount) AS total_amount from DailyPrizeWinners group by prmGoodsIdx desc) c
on a.idx = c.prmGoodsIdx
left join DailyPrizeWinners d
on a.idx = d.prmGoodsIdx
and d.dt = curdate()
where a.status = 1 and b.status = 1; */
		
		$this->db->select('a.*, b.name, c.total_amount, d.amount as today_amount');
		$this->db->from('PromotionGoods a');
		$this->db->join('Goods b', 'a.goodsIdx = b.idx', 'left');
		$this->db->join('(select prmGoodsIdx, SUM(amount) AS total_amount from DailyPrizeWinners group by prmGoodsIdx desc) c', 'a.idx = c.prmGoodsIdx', 'left');
		$this->db->join('DailyPrizeWinners d', 'a.idx = d.prmGoodsIdx and d.dt = curdate()', 'left');
		$this->db->where('a.status', 1);
		$this->db->where('b.status', 1);
		return $this->db->get()->result();
	}
	
	public function getListId(){
		$this->db->select('a.idx');
		$this->db->from('PromotionGoods a');
		$this->db->join('Goods b', 'a.goodsIdx = b.idx', 'left');
		$this->db->where('a.status', 1);
		$this->db->where('b.status', 1);
		return $this->db->get()->result();
	}
	
	public function get($idx){
		if(!$ea_id) return;
		return $this->db->get_where('EventsNotices', array('idx'=>$idx))->row();
	}
	
	public function update($idx='', $data = array()){
		if(!$idx || empty($data)) return;

		$this->db->where('idx', $idx);
		$this->db->update('PromotionGoods', $data);
	}
	
}
