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
    // 경고메세지를 경고창으로
    
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
    
    public function get_text($str, $html=0)
    {
    	/* 3.22 막음 (HTML 체크 줄바꿈시 출력 오류때문)
    	 $source[] = "/  /";
    	 $target[] = " &nbsp;";
    	 */
    
    	// 3.31
    	// TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    	if ($html == 0) {
    		$str = $this->html_symbol($str);
    	}
    
    	$source[] = "/</";
    	$target[] = "&lt;";
    	$source[] = "/>/";
    	$target[] = "&gt;";
    	//$source[] = "/\"/";
    	//$target[] = "&#034;";
    	$source[] = "/\'/";
    	$target[] = "&#039;";
    	//$source[] = "/}/"; $target[] = "&#125;";
    	if ($html) {
    		$source[] = "/\n/";
    		$target[] = "<br/>";
    	}
    
    	return preg_replace($source, $target, $str);
    }
    
    // 3.31
    // HTML SYMBOL 변환
    // &nbsp; &amp; &middot; 등을 정상으로 출력
    public function html_symbol($str)
    {
    	return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
    }
    
    public function test()
    {
    	echo 'test ok';
    }
}

/* End of file Someclass.php */