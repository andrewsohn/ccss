<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('encrypt');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->model('cs_prereserve_applicant');
		$this->load->model('cs_user');
		$this->load->model('cs_promotion_goods');
		$this->load->model('cs_dp_winner');
	}
	
	public function index()
	{

		//당첨기능
		$pgoods = $this->winPrize();
		
		$win_product = '';
		for($i=0; $i<count($pgoods); $i++){
			if($this->tryWin($pgoods[$i])){
				echo $win_product = $pgoods[$i]->idx;
				break;
			}
		}
		
		if($win_product){
			$this->cs_dp_winner->insert($win_product);
		}
		$this->common->print_r2($pgoods);
		//오류코드 출력, 정상일때 인덱스 넘김
		//echo $idx;
	}

	function winPrize(){
		return $this->cs_promotion_goods->getList();
	}
	function tryWin($product=array()){
		$win_yn = 0;
		$cus = rand (1, $product->winningRate);
		$win = rand (1, $product->winningRate);
		if($cus === $win){
			$win_yn = 1;
		}
		return $win_yn;
	}
	
}