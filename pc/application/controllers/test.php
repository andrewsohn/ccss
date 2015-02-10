<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

require './system/libraries/autoload.php';
require './system/libraries/src/Twitter/TwitterOAuth.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;

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
		
			$id = $this->config->item('fb_id');
			$secret = $this->config->item('fb_secret');
			
			FacebookSession::setDefaultApplication($id, $secret);
			
			$session = new FacebookSession($_SESSION['token'] );
			
			$request = new FacebookRequest($session, 'GET', '/me');
			$response = $request->execute();
			$graph = $response->getGraphObject(GraphUser::className());
			
			$this->common->print_r2($graph);
			
			
		
	}
	
	public function action()
	{
		$str = '';
		$pArr = array();
		//필수 필드 Validation [start]--------------------
		$decodes = array();
		
		
		$data['userName'] = '손손손';
		
		$data['mobileNum'] = '01012341234';
		
		$data['mtype'] = '1';
		
		$data['type'] = '';
		
		$data['charIdx'] = '3';
		
		$message = '';
		$data['content'] = '테스트';
			$message .= $data['content'];
		$message .= '
		
						[캔디크러시소다 사전예약 이벤트]';
		
		$data['regIP'] = $_SERVER['REMOTE_ADDR'];
		$data['status'] = 1;
		$data['registDt'] = date("Y-m-d H:i:s");

		//당첨기능
		$pgoods = $this->winPrize();
		
		$win_product = '';
		for($i=0; $i<count($pgoods); $i++){
			if($this->tryWin($pgoods[$i])){
				$win_product = $pgoods[$i]->idx;
				break;
			}
		}
		
		if($win_product){
			$this->cs_dp_winner->insert($win_product);
			$data['prmGoodsIdx'] = $win_product;
			$pArr['prmGood'] = $win_product;
		}
		
		//reservation 테이블 저장
		$idx = $this->cs_prereserve_applicant->insertApply($data);
	
		if(!$idx){
			echo '0';
			exit;
		}
		
		$pArr['idx'] = $idx;
		
		//오류코드:0 출력, 정상일때 인덱스 넘김
		foreach ($pArr as $key => $value){
			$str .= $key.':'.$value.'||';
		}
		//print_r($data);
		echo $str;
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