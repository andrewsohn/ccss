<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ApplicantAction2 extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('common');
	}
	
	public function index()
	{
		$this->common->print_r2($_FILES);
		
		$upDate = '20150130';
		$uuid = '9c93cef58b564671be52686008105c1e';
		$fname = 'png';
		$pic_path2 = './data/event/20150130/'.$uuid.'_thumb.'.$fname;
		//CDN 스토리지 FTP 저장
		$this->load->library('ftp');
		$config = array();
		$config['hostname'] = 'hivelab.infralab.net';
		$config['username'] = 'King_ccss';
		$config['password'] = 'gkdlqmfoq!@';
		$config['debug']	= TRUE;
		
		echo $pic_path2;
		$this->ftp->connect($config);
		
		if(file_exists($pic_path2)){
			$folder_path = '/data/event/' . $upDate;
			$file_path = $folder_path.'/'.$uuid.'_thumb.'.$fname;
			$list = $this->ftp->list_files($folder_path);
			$this->common->print_r2($list).'<br>';
			
			if(!is_array($list)){
				
				$this->ftp->mkdir($folder_path, $this->config->item('dir_permission'));
			}
			
			echo $file_path.'ddddd<br>';
			$this->ftp->upload('./data/event/20150130/9c93cef58b564671be52686008105c1e_thumb.png', $file_path, '', $this->config->item('dir_permission'));
		}
		
		$this->ftp->close();
		
	}
}