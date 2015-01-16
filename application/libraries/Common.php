<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Common {
	private $CI;
	
	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library('user_agent');
	}
	
	public function goto_url($url)
	{
		$url = str_replace("&amp;", "&", $url);
	
		if (!headers_sent())
			header('Location: '.$url);
		else {
			echo '<script>';
			echo 'location.replace("'.$url.'");';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			echo '</noscript>';
		}
		exit;
	}
	
    // 경고메세지를 경고창으로
    public function alert($msg='', $url='')
    {
    	if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';
    
    	$msg2 = str_replace("\\n", "<br>", $msg);
    	
    	if (!$url){
    		$url = $this->CI->agent->referrer();
    	}
    	
		$str = '<script>';    	
    	$str .= 'alert("'.strip_tags($msg).'");';
    	if ($url) {
    		$str .= 'document.location.replace("'.str_replace('&amp;', '&', $url).'");';
    	}else {
    		$str .= 'history.back();';
    	}
    	$str .= '</script>';
    	
    	echo $str;
    }
    
    public function print_r2($var)
    {
    	ob_start();
    	print_r($var);
    	$str = ob_get_contents();
    	ob_end_clean();
    	$str = str_replace(" ", "&nbsp;", $str);
    	echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
    }
    
    public function sql_password($value)
    {
    	// mysql 4.0x 이하 버전에서는 password() 함수의 결과가 16bytes
    	// mysql 4.1x 이상 버전에서는 password() 함수의 결과가 41bytes
    	$query = $this->CI->db->query(" select password('$value') as pass ");
    	$row = $query->row();
    	return $row->pass;
    }
    
    // 회원 정보를 얻는다.
    public function get_member($mb_id, $fields='*')
    {
    	if(!$mb_id) return;
    	$query = $this->CI->db->query(" select $fields from cs_member where mb_id = TRIM('$mb_id') ");
    	return $query->row();
    }
    
    public function test()
    {
    	echo 'test ok';
    }
}

/* End of file Someclass.php */