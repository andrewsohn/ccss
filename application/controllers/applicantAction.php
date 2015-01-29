<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

require './system/libraries/autoload.php';

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
		$this->load->helper('url');
		$this->load->model('CsAdminEventTeaser');
		$this->load->model('CsAdminEventApplicant');
		$this->load->model('CsUser');
	}
	
	public function index()
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
		
		if($data['type'] == '1'){ //페이스북 코드 1
			$id = '348697705319104';
			$secret = '72acca56f341803ddada56ecefb4ad11';
			
			FacebookSession::setDefaultApplication($id, $secret);
			
			$session = new FacebookSession($_SESSION['token'] );
			
			$request = new FacebookRequest($session, 'GET', '/me');
			$response = $request->execute();
			$graph = $response->getGraphObject(GraphUser::className());
			
			$this->common->print_r2($graph);
			
			$uarr['id'] = $graph->getId();
			$uarr['name'] = $graph->getName();
			$uarr['photoUrl'] = 'https://graph.facebook.com/'.$id.'/picture?type=square';
			
			$user = $this->userCheck($uarr,$data['type']);
			$this->common->print_r2($user);
			if(!empty($user)){
				/*
				 *
				 * idx                  VARCHAR(32) NOT NULL COMMENT '이벤트 게시글 인덱스',
				 eventIdx             ok
				 userType             ok
				 status               ok
				 subject              ok
				 content              ok
				 regIP				 ok
				 registDt             ok
				 	
				 *  */
				$data['userId'] = $user->id;
				$data['userType'] = $user->type;
				$data['regIP'] = $_SERVER['REMOTE_ADDR'];
				$data['status'] = 1;
				$data['subject'] = '';
				
				$data['content'] = '';
				if($this->input->post('bf_content', TRUE)){
					$data['content'] = trim($this->input->post('bf_content', TRUE));
					$data['content'] = $this->common->conv_content($this->common->conv_unescape_nl($data['content']), 0);
				}
				
				if(!$this->insertApply($data)){
					$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
					exit;
				}
				/* echo "data:<br>";
				$this->common->print_r2($data); */
			}
		}else{ //트위터 코드 2
			/*
			 *
			 * idx                  VARCHAR(32) NOT NULL COMMENT '이벤트 게시글 인덱스',
			 eventIdx             ok
			 userId 				 ok
			 userType             SMALLINT UNSIGNED NOT NULL COMMENT '회원가입 종류',
			 status               SMALLINT UNSIGNED NOT NULL COMMENT '상태',
			 type                 ok
			 subject              VARCHAR(100) COMMENT '제목',
			 content              TEXT NULL COMMENT '내용',
			 refIdx               BIGINT UNSIGNED NULL COMMENT '참조 게시글 인덱스',
			 hits                 MEDIUMINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '조회수',
			 regIP				 VARCHAR(39) NOT NULL COMMENT '등록 IPv4 or IPv6',
			 registDt             DATETIME NULL COMMENT '등록일'
			
			 id                   VARCHAR(25) NULL COMMENT '회원 아이디',
			 type                 SMALLINT UNSIGNED NOT NULL COMMENT '회원가입 종류',
			 name                 VARCHAR(50) NULL COMMENT '사용자명',
			 photoUrl             VARCHAR(350) NULL COMMENT '사진 URL',
			 visible              ENUM('N', 'Y') NOT NULL DEFAULT 'Y' COMMENT '활성/비활성',
			 registDt             DATETIME NULL COMMENT '등록일'
			 *  */
			
			
			
			
		}
		
		
		
		
		
		
		/* 
		 * 모두 검사를 마친 후 DB 저장 정보 
		 * 나중에 idx -> echo $this->universaluid->v4();
		 *  */
		
		
		
		
		
		$msg = implode('<br>', $msg);
		if ($msg) {
			$this->common->alert($msg);
			exit;
		}
		
		$this->common->print_r2($_POST);
		$this->common->print_r2($_FILES);
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
		$res = false;
		
		if(empty($data)){
			$this->common->alert('잘못된 회원정보입니다. 다시 시도하여 주십시요.');
			exit;
		}
		
		$id = $this->CsAdminEventApplicant->insertApply($data);
		
		if($id) $res = true;
		return $res;
	}
	
	function uploadIMG($data=array()){
		$upload_max_filesize = ini_get('upload_max_filesize');
		// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
		@mkdir('./data/event/'.$bo_table, $this->config->item('dir_permission'));
		@chmod('./data/event/'.$bo_table, $this->config->item('dir_permission'));
		
		$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
		
		// 가변 파일 업로드
		$file_upload_msg = '';
		$upload = array();
		for ($i=0; $i<count($_FILES['bf_file']['name']); $i++) {
		    $upload[$i]['file']     = '';
		    $upload[$i]['source']   = '';
		    $upload[$i]['filesize'] = 0;
		    $upload[$i]['image']    = array();
		    $upload[$i]['image'][0] = '';
		    $upload[$i]['image'][1] = '';
		    $upload[$i]['image'][2] = '';
		
			$upload[$i]['del_check'] = false;
			
		    $tmp_file  = $_FILES['bf_file']['tmp_name'][$i];
		    $filesize  = $_FILES['bf_file']['size'][$i];
		    $filename  = $_FILES['bf_file']['name'][$i];
		    $filename  = $this->common->get_safe_filename($filename);
		
		    // 서버에 설정된 값보다 큰파일을 업로드 한다면
		    if ($filename) {
		        if ($_FILES['bf_file']['error'][$i] == 1) {
		            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
		            continue;
		        }
		        else if ($_FILES['bf_file']['error'][$i] != 0) {
		            $file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
		            continue;
		        }
		    }
		
		    if (is_uploaded_file($tmp_file)) {
		        // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
		        if (!$is_admin && $filesize > $board['bo_upload_size']) {
		            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format($board['bo_upload_size']).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
		            continue;
		        }
		
		        //=================================================================\
		        // 090714
		        // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
		        // 에러메세지는 출력하지 않는다.
		        //-----------------------------------------------------------------
		        $timg = @getimagesize($tmp_file);
		        // image type
		        if ( preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
		             preg_match("/\.({$config['cf_flash_extension']})$/i", $filename) ) {
		            if ($timg['2'] < 1 || $timg['2'] > 16)
		                continue;
		        }
		        //=================================================================
		
		        $upload[$i]['image'] = $timg;
		
		        // 4.00.11 - 글답변에서 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
		        if ($w == 'u') {
		            // 존재하는 파일이 있다면 삭제합니다.
		            $row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
		            @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
		            // 이미지파일이면 썸네일삭제
		            if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
		                delete_board_thumbnail($bo_table, $row['bf_file']);
		            }
		        }
		
		        // 프로그램 원래 파일명
		        $upload[$i]['source'] = $filename;
		        $upload[$i]['filesize'] = $filesize;
		
		        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
		        $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);
		
		        shuffle($chars_array);
		        $shuffle = implode('', $chars_array);
		
		        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
		        $upload[$i]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $filename)));
		
		        $dest_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$upload[$i]['file'];
		
		        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
		        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);
		
		        // 올라간 파일의 퍼미션을 변경합니다.
		        chmod($dest_file, G5_FILE_PERMISSION);
		    }
		}
	}
}