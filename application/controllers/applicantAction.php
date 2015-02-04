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

class ApplicantAction extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('ftp');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->model('csadmineventapplicant');
		$this->load->model('csuser');
	}
	
	public function index()
	{
		$w = '';
		if($this->input->post('w', TRUE)){
			$w = $this->input->post('w', TRUE);
		}
		
		if(!in_array($w, array("", "s"))){
			$this->common->alert('죄송합니다. 저장오류입니다.');
			exit;
		}
		
		$msg = array();
		$data = array();
		
		//필수 필드 Validation [start]--------------------
		$data['type'] = '';
		if($this->input->post('snsId', TRUE)){
			$data['type'] = trim($this->input->post('snsId', TRUE));
		}else{
			$msg[] = '죄송합니다. 저장오류입니다.';
		}
		
		$data['eventIdx'] = '';
		if($this->input->post('et_id', TRUE)){
			$data['eventIdx'] = trim($this->input->post('et_id', TRUE));
		}else{
			$msg[] = '죄송합니다. 저장오류입니다.';
		}
		
		$data['content'] = '';
		if($this->input->post('bf_content', TRUE)){
			$data['content'] = trim($this->input->post('bf_content', TRUE));
			$data['content'] = $this->common->conv_content($this->common->conv_unescape_nl($data['content']), 0);
		}else{
			$msg[] = '죄송합니다. 저장오류입니다.';
		}
		
		$pic_path = '';
		$data['refIdx'] = '';
		if($w == 's'){
			if($this->input->post('src', TRUE)){
				$pic_path = trim($this->input->post('src', TRUE));
			}else{
				$msg[] = '공유한 이미지 경로가 잘 못 되었습니다.\n다시 시도하여 주십시요.';
			}
			
			if($this->input->post('refIdx', TRUE)){
				$data['refIdx'] = trim($this->input->post('refIdx', TRUE));
			}else{
				$msg[] = '공유한 게시글이 없습니다.\n다시 시도하여 주십시요.';
			}
		}
		
		$msg = implode('<br>', $msg);
		if ($msg) {
			$this->common->alert($msg);
			exit;
		}
		
		if($data['type'] == '1'){ //페이스북 코드 1
			$id = $this->config->item('fb_id');
			$secret = $this->config->item('fb_secret');
			
			FacebookSession::setDefaultApplication($id, $secret);
			
			$session = new FacebookSession($_SESSION['token'] );
			
			$request = new FacebookRequest($session, 'GET', '/me');
			$response = $request->execute();
			$graph = $response->getGraphObject(GraphUser::className());
			
			//$this->common->print_r2($graph);
			
			$uarr['id'] = $graph->getId();
			$uarr['name'] = $graph->getName();
			$uarr['photoUrl'] = 'https://graph.facebook.com/'.$uarr['id'].'/picture?type=square';
			
			$user = $this->userCheck($uarr,$data['type']);
			//$this->common->print_r2($user);
			if(!empty($user)){
				$data['userId'] = $user->id;
				$data['userType'] = $user->type;
				$data['regIP'] = $_SERVER['REMOTE_ADDR'];
				$data['status'] = 1;
				$data['subject'] = '';
				$data['registDt'] = date("Y-m-d H:i:s");
				
				if($w == 's'){
					$fname = explode('.',$pic_path);
					$data['photoType'] = $fname[count($fname)-1];
					$filename = 'ccss_image_share.'.$data['photoType'];
				}else{
					$fname = explode('.',$_FILES['bf_file_fb']['name']);
					$data['photoType'] = $fname[1];
				}
				
				$data['uuid'] = $this->insertApply($data);
				
				if(!$data['uuid']){
					$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
					exit;
				}
				
				if($w != 's'){
					//년월일별 폴더 생성
					$upDate = str_replace("-","",substr($data['registDt'],0,10));
					if (!is_dir('data/event/'.$upDate)) {
						mkdir('./data/event/' . $upDate, $this->config->item('dir_permission'), TRUE);
						chmod('./data/event/' . $upDate, $this->config->item('dir_permission'));
					}
					
					//원본 이미지 저장
					$filename = $data['uuid'].'.'.$fname[1];
					$config1['upload_path'] = './data/event/'.$upDate;
					$config1['allowed_types'] = 'jpg|png';
					$config1['file_name'] = $filename;
					$config1['max_size']	= '2097152';
					$config1['max_width']  = '10240';
					$config1['max_height']  = '7680';
					
					$this->load->library('upload', $config1);
					
					if ( ! $this->upload->do_upload('bf_file_fb')){
						$this->removeAllData($data);
						$this->common->alert($this->upload->display_errors());
						exit;
					}
					
					//썸네일(320x240) 이미지 저장
					$config['image_library'] = 'gd2';
					$config['source_image']	= './data/event/'.$upDate.'/'.$filename;
					$config['new_image']	= './data/event/'.$upDate.'/'.$data['uuid'].'.'.$fname[1];
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
					$config['width']	= 320;
					$config['height']	= 240;
					
					$this->load->library('image_lib', $config);
					
					$pic_path = $config1['upload_path'].'/'.$config1['file_name'];
					
					if ( ! $this->image_lib->resize())
					{
						$this->removeAllData($data, $pic_path);
						$this->common->alert($this->upload->display_errors());
						exit;
					}
					
					$pic_path2 = './data/event/'.$upDate.'/'.$data['uuid'].'_thumb.'.$fname[1];
					//CDN 스토리지 FTP 저장
					$config = array();
					$config['hostname'] = 'hivelab.infralab.net';
					$config['username'] = 'King_ccss';
					$config['password'] = 'gkdlqmfoq!@';
					$config['debug']	= false;
					
					$this->ftp->connect($config);
					
					if(file_exists($pic_path2)){
						$folder_path = '/data/event/'.$upDate;
						$file_path = $folder_path.'/'.$data['uuid'].'_thumb.'.$fname[1];
						$list = $this->ftp->list_files($folder_path);
						//$this->common->print_r2($list).'<br>';
					
						if(!is_array($list)){
							$this->ftp->mkdir($folder_path, $this->config->item('dir_permission'));
						}
					
						//echo $file_path.'ddddd<br>';
						$this->ftp->upload($pic_path2, $pic_path2, '', $this->config->item('dir_permission'));
					}
					
					$this->ftp->close();
				}
				
				$post_type = 'url';
				if($w != 's'){
					$post_type = 'source';
					$pic_path = new CURLFile(realpath($pic_path), 'image/'.$fname[1], $filename);
				}
				
				try {
					$session = new FacebookSession($_SESSION['token']);
					$response = (new FacebookRequest(
							$session, 'POST', '/me/photos', array(
									$post_type => $pic_path,
									'message' => trim($this->input->post('bf_content', TRUE))
							)
					))->execute()->getGraphObject();
							$this->session->set_flashdata('apply_complete','teaser');
							redirect('teaser#snsBtn');
				} catch(FacebookRequestException $e) {
						
					echo "Exception occured, code: " . $e->getCode();
					echo " with message: " . $e->getMessage();
						
					$this->removeAllData($data, $pic_path,$pic_path2);
				}
				
			}
		}else if($data['type'] == '2'){ //트위터 코드 2
			$this->common->print_r2($_SESSION);
			
			$id = $this->config->item('tt_id');
			$secret = $this->config->item('tt_secret');
			
			$connection = new TwitterOAuth($id,$secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
			$credentials = $connection->getAccessToken($_SESSION["oauth_verifier"]);
			
			$user = $connection->get("account/verify_credentials");
			//$this->common->print_r2($user);
			
			$uarr['id'] = $user->id;
			$uarr['name'] = $user->name;
			$uarr['photoUrl'] = $user->profile_image_url;
			
			$user2 = $this->userCheck($uarr,$data['type']);
			//$this->common->print_r2($user2);
			if(!empty($user2)){
				$data['userId'] = $user2->id;
				$data['userType'] = $user2->type;
				$data['regIP'] = $_SERVER['REMOTE_ADDR'];
				$data['status'] = 1;
				$data['subject'] = '';
				$data['registDt'] = date("Y-m-d H:i:s");
				
				if($w == 's'){
					$fname = explode('.',$pic_path);
					$data['photoType'] = $fname[count($fname)-1];
					$filename = 'ccss_image_share.'.$data['photoType'];
				}else{
					$fname = explode('.',$_FILES['bf_file_tw']['name']);
					$data['photoType'] = $fname[1];
				}
				
				$data['uuid'] = $this->insertApply($data);
				
				if(!$data['uuid']){
					$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
					exit;
				}
				
				if($w != 's'){
					$upDate = str_replace("-","",substr($data['registDt'],0,10));
					if (!is_dir('data/event/'.$upDate)) {
						mkdir('./data/event/' . $upDate, $this->config->item('dir_permission'), TRUE);
						chmod('./data/event/' . $upDate, $this->config->item('dir_permission'));
					}
					
					//원본 이미지 저장
					$filename = $data['uuid'].'.'.$fname[1];
					$config['upload_path'] = './data/event/'.$upDate;
					$config['allowed_types'] = 'jpg|png';
					$config['file_name'] = $filename;
					$config['max_size']	= '2097152';
					$config['max_width']  = '10240';
					$config['max_height']  = '7680';
					
					$this->load->library('upload', $config);
					
					if ( ! $this->upload->do_upload('bf_file_tw')){
						$this->removeAllData($data);
						$this->common->alert($this->upload->display_errors());
						exit;
					}
					
					$upPath = $config['upload_path'];
					$fileName = $config['file_name'];
					
					//썸네일(320x240) 이미지 저장
					$config['image_library'] = 'gd2';
					$config['source_image']	= './data/event/'.$upDate.'/'.$filename;
					$config['new_image']	= './data/event/'.$upDate.'/'.$data['uuid'].'.'.$fname[1];
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
					$config['width']	= 320;
					$config['height']	= 240;
					
					$this->load->library('image_lib', $config);
					
					$pic_path = $config1['upload_path'].'/'.$config1['file_name'];
					
					if ( ! $this->image_lib->resize())
					{
						$this->removeAllData($data, $pic_path);
						$this->common->alert($this->upload->display_errors());
						exit;
					}
					
					$pic_path2 = './data/event/'.$upDate.'/'.$data['uuid'].'_thumb.'.$fname[1];
					//CDN 스토리지 FTP 저장
					$config = array();
					$config['hostname'] = 'hivelab.infralab.net';
					$config['username'] = 'King_ccss';
					$config['password'] = 'gkdlqmfoq!@';
					$config['debug']	= false;
					
					$this->ftp->connect($config);
					
					if(file_exists($pic_path2)){
						$folder_path = '/data/event/'.$upDate;
						$file_path = $folder_path.'/'.$data['uuid'].'_thumb.'.$fname[1];
						$list = $this->ftp->list_files($folder_path);
						//$this->common->print_r2($list).'<br>';
					
						if(!is_array($list)){
							$this->ftp->mkdir($folder_path, $this->config->item('dir_permission'));
						}
					
						//echo $file_path.'ddddd<br>';
						$this->ftp->upload($pic_path2, $pic_path2, '', $this->config->item('dir_permission'));
					}
					
					$this->ftp->close();
				}
				
				if($w != 's'){
					$pic_path = $upPath.'/'.$fileName;
					
					$image = file_get_contents($pic_path);
					$params = array(
							'media[]'  => $image,
							'status'  => trim($this->input->post('bf_content', TRUE))
					);
					
					$response =$connection->post('statuses/update_with_media', $params, true);
					$this->session->set_flashdata('apply_complete','teaser');
					redirect('teaser#snsBtn');
				}else{
					$image = file_get_contents($pic_path);
					$params = array(
							'media[]'  => $image,
							'status'  => trim($this->input->post('bf_content', TRUE))
					);
					
					$response =$connection->post('statuses/update_with_media', $params, true);
					$this->session->set_flashdata('apply_complete','teaser');
					redirect('teaser#snsBtn');
				}
			}
		}
	}
	
	public function pre(){
		if($this->input->post('sns', TRUE) == 'fb'){
			$id = $this->config->item('fb_id');
			$secret = $this->config->item('fb_secret');
			
			FacebookSession::setDefaultApplication($id, $secret);
			
			$helper = new FacebookRedirectLoginHelper(site_url('teaser'));
			$session = $helper->getSessionFromRedirect();
			
			$scope = array('publish_actions');
			$fbahref = $helper->getLoginUrl($scope);
			redirect($fbahref);
		}else if($this->input->post('sns', TRUE) == 'tt'){
			echo 'tt';
		}
	}
	
	function removeAllData($data=array(),$pic_path='', $pic_path2=''){
		if(empty($data)) return;
	
		$this->csadmineventapplicant->delete($data);
	
		if(!isset($pic_path)) return;
		delete_files($pic_path);
		
		if(!isset($pic_path2)) return;
		delete_files($pic_path2);
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
	
	function insertApply($data=array()){
		if(empty($data)){
			$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
			exit;
		}
		return $this->csadmineventapplicant->insertApply($data);
	}
	
	function uploadIMG($data=array(), $files=array()){
	}
}