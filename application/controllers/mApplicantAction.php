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

class MApplicantAction extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
		$this->load->library('ftp');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->model('CsAdminEventApplicant');
		$this->load->model('CsUser');
	}
	
	public function index(){
		$w = '';
		if($this->input->post('w', TRUE)){
			$w = $this->input->post('w', TRUE);
		}
		
		if(!in_array($w, array("", "u"))){
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
		
		/* echo trim($this->input->post('snsId', TRUE)).'<br>';
		$this->common->print_r2($data); */
		$msg = implode('<br>', $msg);
		if ($msg) {
			$this->common->alert($msg);
			exit;
		}
		
		if($data['type'] == '1'){ //페이스북 코드 1
			$id = '348697178652490';
			$secret = '48b051b1e8230b0f1b44451055b7c921';
				
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
		
				$fname = explode('.',$_FILES['bf_file_fb']['name']);
				$data['photoType'] = $fname[1];
				$data['uuid'] = $this->insertApply($data);
		
				if(!$data['uuid']){
					$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
					exit;
				}
		
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
		
				//페이스북 업로드
				try {
					$session = new FacebookSession($_SESSION['token']);
					$response = (new FacebookRequest(
							$session, 'POST', '/me/photos', array(
									'source' => new CURLFile(realpath($pic_path), 'image/'.$fname[1], $filename),
									'message' => trim($this->input->post('bf_content', TRUE))
							)
					))->execute()->getGraphObject();
							redirect('m/teaser#snsBtn');
				} catch(FacebookRequestException $e) {
		
					echo "Exception occured, code: " . $e->getCode();
					echo " with message: " . $e->getMessage();
		
					$this->removeAllData($data, $pic_path,$pic_path2);
				}
			}
		}else if($data['type'] == '2'){ //트위터 코드 2
			$this->common->print_r2($_SESSION);
				
			$id = 'NIILXSwqZ65evPP4bFfGFQLmz';
			$secret = 'd2z4sL59dDquqqWE0cY2LRMfg1CSEnQvkq5Ru97gCPQjNQQnEb';
				
			$connection = new TwitterOAuth($id,$secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
			$credentials = $connection->getAccessToken($_SESSION["oauth_verifier"]);
				
			$user = $connection->get("account/verify_credentials");
			//$this->common->print_r2($user);
				
			$uarr['id'] = $user->id;
			$uarr['name'] = $user->name;
			$uarr['photoUrl'] = $user->profile_image_url;
				
			$user2 = $this->userCheck($uarr,$data['type']);
			$this->common->print_r2($user2);
			if(!empty($user2)){
				$data['userId'] = $user2->id;
				$data['userType'] = $user2->type;
				$data['regIP'] = $_SERVER['REMOTE_ADDR'];
				$data['status'] = 1;
				$data['subject'] = '';
				$data['registDt'] = date("Y-m-d H:i:s");
		
				$fname = explode('.',$_FILES['bf_file_tw']['name']);
				$data['photoType'] = $fname[1];
		
				$data['uuid'] = $this->insertApply($data);
		
				if(!$data['uuid']){
					$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
					exit;
				}
		
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
		
				$pic_path = $upPath.'/'.$fileName;
		
				$this->common->print_r2($data);
		
				$image = file_get_contents($pic_path);
				$params = array(
						'media[]'  => $image,
						'status'  => trim($this->input->post('bf_content', TRUE))
				);
		
				$response =$connection->post('statuses/update_with_media', $params, true);
				redirect('m/teaser#snsBtn');
			}
		}
	}
	public function teaser()
	{
		$data = $this->session->all_userdata();
		$this->_header();
		
		$view = '';
		if(isset($_REQUEST['et_id'])){
			if($_REQUEST['et_id']){
				$view = $this->CsAdminEventTeaser->get($_REQUEST['et_id']);
			}
		}
		
		if(empty($view))
			$view = $this->CsAdminEventTeaser->getLastLive();
		
		$data['view'] = $view;
		if(!empty($view)){
			$clist = $this->CsAdminEventApplicant->getListMainMob($view->idx);
			$data['clist'] = $clist;
		}
		//echo $view->idx;
		//$this->common->print_r2($clist);
		
		$board_list = $this->CsAdminEventTeaser->getListLive();
		
		$data['blist'] = $board_list;
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->view('mteaser', $data);
		
		$this->_footer();
	}
	
	public function applyAction()
	{
		if($this->input->post('sns', TRUE)){
			$idx = $this->input->post('sns', TRUE);
			$sns = $this->CsSns->get($idx);
			$this->output->set_header('Content-Type: application/json; charset=utf-8');
			echo json_encode($sns);
		}
	}
	
	public function getMoreList()
	{
		if($this->input->post('idx', TRUE) && $this->input->post('idx2', TRUE)){
			$idx = $this->input->post('idx', TRUE);
			$idx2 = $this->input->post('idx2', TRUE);
			//echo json_encode($idx.':'.$idx2);
			$clist = $this->CsAdminEventApplicant->getListMoreMob($idx,$idx2);
			$this->output->set_header('Content-Type: application/json; charset=utf-8');
			/* */
			$str = '';
			for($i=0; $i<count($clist); $i++){
				$str .= '<li>';
				$str .= '<a href="https://www.facebook.com/profile.php?id='.$clist[$i]->userId.'">';
				$str .= '<span class="tmb"><img src="'.$this->config->item('asset_url').'/PC/img/@thumb/thumb.jpg" style="height:100%" alt=""></span>';
				$str .= '<div class="txt">';
				$str .= '<span class="tmb"><img src="'.$clist[$i]->photoUrl.'" style="width:100%" alt="'.$clist[$i]->userName.'프로필사진"></span>';
				$str .= '<em>'.$clist[$i]->userName.'</em>'.$this->common->getTime($clist[$i]->registDt);
				$str .= '<p>'.$this->common->getShortenText($clist[$i]->content).'</p>';
				$str .= '</div>';
				$str .= '</a>';
				$str .= '<button class="';
				if($clist[$i]->type == 1) $str .= 'btn_fb'; else $str .= 'btn_fb';
				$str .= '"><span>';
				if($clist[$i]->type == 1) $str .= '페이스북'; else $str .= '트위터';
				$str .= ' 공유</span></button></li>';
				if($i == count($clist)-1)
					$str .= '|||'.$clist[$i]->idx;
			}
			
			echo json_encode($str);
		}
	}
	
	public function applicantAction()
	{
		$w = '';
		if($this->input->post('w', TRUE)){
			$w = $this->input->post('w', TRUE);
		}
		
		if(!in_array($w, array("", "u"))){
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
		
		$msg = implode('<br>', $msg);
		if ($msg) {
			$this->common->alert($msg);
			exit;
		}
		
		if($data['type'] == '1'){ //페이스북 코드 1
			$id = '348697178652490';
			$secret = '48b051b1e8230b0f1b44451055b7c921';
			
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
				
				$fname = explode('.',$_FILES['bf_file_fb']['name']);
				$data['photoType'] = $fname[1];
				$data['uuid'] = $this->insertApply($data);
				
				if(!$data['uuid']){
					$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
					exit;
				}
				
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
				
				//페이스북 업로드
				try {
					$session = new FacebookSession($_SESSION['token']);
					$response = (new FacebookRequest(
							$session, 'POST', '/me/photos', array(
									'source' => new CURLFile(realpath($pic_path), 'image/'.$fname[1], $filename),
									'message' => trim($this->input->post('bf_content', TRUE))
							)
					))->execute()->getGraphObject();
							redirect('m/teaser#snsBtn');
				} catch(FacebookRequestException $e) {
				
					echo "Exception occured, code: " . $e->getCode();
					echo " with message: " . $e->getMessage();
				
					$this->removeAllData($data, $pic_path,$pic_path2);
				}
			}
		}else if($data['type'] == '2'){ //트위터 코드 2
			$this->common->print_r2($_SESSION);
			
			$id = 'NIILXSwqZ65evPP4bFfGFQLmz';
			$secret = 'd2z4sL59dDquqqWE0cY2LRMfg1CSEnQvkq5Ru97gCPQjNQQnEb';
			
			$connection = new TwitterOAuth($id,$secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
			$credentials = $connection->getAccessToken($_SESSION["oauth_verifier"]);
			
			$user = $connection->get("account/verify_credentials");
			//$this->common->print_r2($user);
			
			$uarr['id'] = $user->id;
			$uarr['name'] = $user->name;
			$uarr['photoUrl'] = $user->profile_image_url;
			
			$user2 = $this->userCheck($uarr,$data['type']);
			$this->common->print_r2($user2);
			if(!empty($user2)){
				$data['userId'] = $user2->id;
				$data['userType'] = $user2->type;
				$data['regIP'] = $_SERVER['REMOTE_ADDR'];
				$data['status'] = 1;
				$data['subject'] = '';
				$data['registDt'] = date("Y-m-d H:i:s");
				
				$fname = explode('.',$_FILES['bf_file_tw']['name']);
				$data['photoType'] = $fname[1];
				
				$data['uuid'] = $this->insertApply($data);
				
				if(!$data['uuid']){
					$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
					exit;
				}
				
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
				
				$pic_path = $upPath.'/'.$fileName;
				
				$this->common->print_r2($data);
				
				$image = file_get_contents($pic_path);
				$params = array(
						'media[]'  => $image,
						'status'  => trim($this->input->post('bf_content', TRUE))
				);
				
				$response =$connection->post('statuses/update_with_media', $params, true);
				redirect('m/teaser#snsBtn');
			}
		}
	}
	function removeAllData($data=array(),$pic_path='', $pic_path2=''){
		if(empty($data)) return;
	
		$this->CsAdminEventApplicant->delete($data);
	
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
		$user = $this->CsUser->checkNSave($user_arr);
	
		return $user;
	}
	
	function insertApply($data=array()){
		if(empty($data)){
			$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
			exit;
		}
		return $this->CsAdminEventApplicant->insertApply($data);
	}
	
	function uploadIMG($data=array(), $files=array()){
	}
}