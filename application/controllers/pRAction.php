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

class PRAction extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('encrypt');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->model('cs_prereserve_applicant');
		$this->load->model('csuser');
	}
	
	public function index()
	{
		$idx = '';
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
		
		$data['charIdx'] = '';
		if($this->input->post('charIdx', TRUE)){
			$data['charIdx'] = $this->input->post('charIdx', TRUE);
		}
		
		$data['content'] = '';
		if($this->input->post('content', TRUE)){
			$data['content'] = trim($this->input->post('content', TRUE));
			$data['content'] = $this->common->conv_content($this->common->conv_unescape_nl($data['content']), 0);
		}
		
		//sns 글 공유
		//당첨기능
		//오류코드 출력
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
				
			//$this->common->print_r2($graph);
			
			if(!empty($user)){
				$data['regIP'] = $_SERVER['REMOTE_ADDR'];
				$data['status'] = 1;
				$data['registDt'] = date("Y-m-d H:i:s");
				
				//reservation 테이블 저장
				$idx = $this->cs_prereserve_applicant->insertApply($data);
			
				if(!$idx){
					echo '0';
					exit;
				}
				
				$message = $data['content'].'
						
						[캔디크러시소다 사전예약 이벤트]';
			
				try {
					$session = new FacebookSession($_SESSION['token']);
					$response = (new FacebookRequest(
							$session, 'POST', '/me/feed', array(
									'message' => $message
							)
					))->execute()->getGraphObject();
							$this->session->set_flashdata('apply_complete','pre');
							
				} catch(FacebookRequestException $e) {
			
					//echo "Exception occured, code: " . $e->getCode();
					//echo " with message: " . $e->getMessage();
					echo '0';
					$this->removeAllData($data, $pic_path,$pic_path2);
				}
			
			}
		}else if($data['type'] == 2){
			
		}
		echo $idx;
	}

	function userCheck($user_arr=array(),$type=''){
		$res = false;
	
		if(empty($user_arr) && !isset($type)){
			$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
			exit;
		}
	
		$user_arr['type'] = $type;
		$user = $this->csuser->checkNSave($user_arr);
	
		return $user;
	}
}