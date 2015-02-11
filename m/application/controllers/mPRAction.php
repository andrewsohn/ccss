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

class MPRAction extends CI_Controller {
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
	
	public function index(){
		$str = '';
		$pArr = array();
		//필수 필드 Validation [start]--------------------
		$decodes = array();
		if($this->input->post('enc', TRUE)){
			$decodes = json_decode(trim($this->input->post('enc', TRUE)), true);
		}else{
			echo '0';
			exit;
		}
		
		if($this->input->post('agree', TRUE) != '1'){
			echo '0';
			exit;
		}
		
		$data['userName'] = '';
		if($decodes['name']){
			$data['userName'] = $this->encrypt->decode($decodes['name']);
		}else{
			echo '0';
			exit;
		}
		
		$data['mobileNum'] = '';
		if($decodes['phNum1']){
			$data['mobileNum'] .= $this->encrypt->decode($decodes['phNum1']);
			if($decodes['phNum2']){
				$data['mobileNum'] .= $this->encrypt->decode($decodes['phNum2']);
				if($decodes['phNum3']){
					$data['mobileNum'] .= $this->encrypt->decode($decodes['phNum3']);
				}
			}
		}
		
		if(strlen($data['mobileNum']) < 11){
			echo '0';
			exit;
		}
		
		//이름, 모바일번호 검사
		
		$data['mtype'] = '';
		if($this->input->post('mtype', TRUE)){
			$data['mtype'] = $this->input->post('mtype', TRUE);
		}else{
			echo '0';
			exit;
		}
		
		$data['type'] = '';
		if($this->input->post('snsKind', TRUE)){
			if($this->input->post('snsKind', TRUE) == 'fb')
				$data['type'] = 1;
			else if($this->input->post('snsKind', TRUE) == 'tt')
				$data['type'] = 2;
		}
		
		$data['charIdx'] = 1;
		if($this->input->post('charIdx', TRUE)){
			$data['charIdx'] = $this->input->post('charIdx', TRUE);
		}
		
		$message = '';
		$data['content'] = '';
		if($this->input->post('content', TRUE) && isset($data['type'])){
			$data['content'] = trim($this->input->post('content', TRUE));
			$data['content'] = $this->common->conv_content($this->common->conv_unescape_nl($data['content']), 0);
			$message .= $data['content'];
		}
		$message .= '
		
						[캔디크러시소다 사전예약 이벤트]';
		
		//sns 글 공유
		if($data['type'] == 1){
			$id = $this->config->item('fb_id');
			$secret = $this->config->item('fb_secret');
			
			FacebookSession::setDefaultApplication($id, $secret);
				
			$session = new FacebookSession($_SESSION['token'] );
				
			$request = new FacebookRequest($session, 'GET', '/me');
			$response = $request->execute();
			$graph = $response->getGraphObject(GraphUser::className());
			
			$uarr['id'] = $graph->getId();
			$uarr['name'] = $graph->getName();
			$uarr['photoUrl'] = 'https://graph.facebook.com/'.$uarr['id'].'/picture?type=square';
			
			$user = $this->userCheck($uarr,$data['type']);
				
			//$this->common->print_r2($user);
			
			if(!empty($user)){
				$data['userId'] = $user->id;
				$data['userType'] = $user->type;
			}
		}else if($data['type'] == 2){
			$id = $this->config->item('tt_id');
			$secret = $this->config->item('tt_secret');
				
			$connection = new TwitterOAuth($id,$secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
			$credentials = $connection->getAccessToken($_SESSION["oauth_verifier"]);
		
			$user = $connection->get("account/verify_credentials");
				
			$uarr['id'] = $user->id;
			$uarr['name'] = $user->name;
			$uarr['photoUrl'] = $user->profile_image_url;
				
			$user2 = $this->userCheck($uarr,$data['type']);
				
			if(!empty($user2)){
				$data['userId'] = $user2->id;
				$data['userType'] = $user2->type;
			}
		}
		
		$data['regIP'] = $_SERVER['REMOTE_ADDR'];
		$data['status'] = 1;
		$data['registDt'] = date("Y-m-d H:i:s");

		//당첨기능
		$pgoods = $this->winPrize();
		
		$win_product = '';
		for($i=0; $i<count($pgoods); $i++){
			if($this->tryWin($pgoods[$i])){
				echo $win_product = $pgoods[$i]->idx;
				break;
			}
		}
		
		$pArr['prmGood'] = '';
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
		
		if($data['type'] == 1){
			/* try {
				$session = new FacebookSession($_SESSION['token']);
				$response = (new FacebookRequest(
						$session, 'POST', '/me/feed', array(
								'message' => $message
						)
				))->execute()->getGraphObject();
				$this->session->set_flashdata('apply_complete','pre');
			} catch(FacebookRequestException $e) {
					
				echo '0';
				$this->removeAllData($data, $pic_path,$pic_path2);
			} */
		}else if($data['type'] == 2){
			$params = array(
					'status'  => $message
			);
				
			$response =$connection->post('statuses/update', $params, true);
			$this->session->set_flashdata('apply_complete','pre');
		}
		
		//오류코드:0 출력, 정상일때 인덱스 넘김
		foreach ($pArr as $key => $value){
			$str .= $key.':'.$value.'||';
		}
		echo $str;
	}
	
	public function checkNamePhone()
	{
		$data['userName'] = '';
		if($this->input->post('name', TRUE)){
			$data['userName'] = trim($this->input->post('name', TRUE));
		}
		
		$data['mobileNum'] = '';
		if($this->input->post('phNum1', TRUE)){
			$data['mobileNum'] .= trim($this->input->post('phNum1', TRUE));
			if($this->input->post('phNum2', TRUE)){
				$data['mobileNum'] .= trim($this->input->post('phNum2', TRUE));
				if($this->input->post('phNum3', TRUE)){
					$data['mobileNum'] .= trim($this->input->post('phNum3', TRUE));
				}
			}
		}
		
		//참여인명, 모바일번호 검사
		if(strlen($data['mobileNum']) < 10){
			echo 1;
			exit;
		}
		
		//참여인명, 모바일번호 검사
		/* if($this->cs_prereserve_applicant->checkNamePhone($data)){
			echo 0;
			exit;
		} */
		if($this->cs_prereserve_applicant->checkPhone($data)){
			echo 0;
			exit;
		}
		echo 2;
	}
	
	function winPrize(){
		return $this->cs_promotion_goods->getListLive();
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
	
	function userCheck($user_arr=array(),$type=''){
		$res = false;
	
		if(empty($user_arr) && !isset($type)){
			$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
			exit;
		}
	
		$user_arr['type'] = $type;
		$user = $this->cs_user->checkNSave($user_arr);
	
		return $user;
	}
}