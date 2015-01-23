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
    
    // XSS 관련 태그 제거
    public function clean_xss_tags($str)
    {
    	$str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
    
    	return $str;
    }
    
    // 검색어 특수문자 제거
    public function get_search_string($stx)
    {
    	$stx_pattern = array();
    	$stx_pattern[] = '#\.*/+#';
    	$stx_pattern[] = '#\\\*#';
    	$stx_pattern[] = '#\.{2,}#';
    	$stx_pattern[] = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]+#';
    
    	$stx_replace = array();
    	$stx_replace[] = '';
    	$stx_replace[] = '';
    	$stx_replace[] = '.';
    	$stx_replace[] = '';
    
    	$stx = preg_replace($stx_pattern, $stx_replace, $stx);
    
    	return $stx;
    }
    
    // 스트링값 잘라내기
    public function cut_str($str, $len, $suffix="…")
    {
    	$arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    	$str_len = count($arr_str);
    
    	if ($str_len >= $len) {
    		$slice_str = array_slice($arr_str, 0, $len);
    		$str = join("", $slice_str);
    
    		return $str . ($str_len > $len ? $suffix : '');
    	} else {
    		$str = join("", $arr_str);
    		return $str;
    	}
    }
    
    // 쿼리 스트링 만들기
    public function getQSTR($data=array()){
    	if (isset($_REQUEST['sca']))  {
    		$data['sca'] = $this->common->clean_xss_tags(trim($_REQUEST['sca']));
    			
    	}
    
    	if (isset($_REQUEST['sfl']))  {
    		$data['sfl'] = trim($_REQUEST['sfl']);
    		$data['sfl'] = preg_replace("/[\<\>\'\"\%\=\(\)\s]/", "", $data['sfl']);
    			
    	}
    
    	if (isset($_REQUEST['stx']))  { // search text (검색어)
    		$data['stx'] = $this->common->get_search_string(trim($_REQUEST['stx']));
    			
    	}
    
    	if (isset($_REQUEST['sst']))  {
    		$data['sst'] = trim($_REQUEST['sst']);
    		$data['sst'] = preg_replace("/[\<\>\'\"\%\=\(\)\s]/", "", $data['sst']);
    			
    	}
    
    	if (isset($_REQUEST['sod']))  { // search order (검색 오름, 내림차순)
    		$data['sod'] = preg_match("/^(asc|desc)$/i", $_REQUEST['sod']) ? $_REQUEST['sod'] : '';
    			
    	}
    
    	if (isset($_REQUEST['sop']))  { // search operator (검색 or, and 오퍼레이터)
    		$data['sop'] = preg_match("/^(or|and)$/i", $_REQUEST['sop']) ? $_REQUEST['sop'] : '';
    			
    	}
    
    	if (isset($_REQUEST['spt']))  { // search part (검색 파트[구간])
    		$data['spt'] = (int)$_REQUEST['spt'];
    			
    	}
    
    	if (isset($_REQUEST['page'])) { // 리스트 페이지
    		$data['page'] = (int)$_REQUEST['page'];
    			
    	}
    
    	return $data;
    }
    
    // 데이터 array -> 쿼리스트링 만들기
    public function getArrQstr($data=array())
    {
    	$qstr = implode('&amp;', array_map(function ($v, $k) { return $k . '=' . $v; }, $data, array_keys($data)));
    	return $qstr;
    }
    
    // 시간 HR 옵션
    public function printHrs($selected='')
    {
    	$str = '';
    	for($i=0; $i<24; $i++){
    		if($i<10){
    			$num = '0'.$i;
    		}else{
    			$num = $i;
    		}
    		
    		$slt = '';
    		if($selected == $num){
    			$slt = ' selected';
    		}
    		$str .= '<option value="'.$num.'"'.$slt.'>'.$num.'시</option>';
    	}
    	return $str;
    }
    
    // 분 MIN 옵션
    public function printMin($selected='')
    {
    	$str = '';
    	for($i=0; $i<60; $i++){
    		if($i<10){
    			$num = '0'.$i;
    		}else{
    			$num = $i;
    		}
    		
    		$slt = '';
    		if($selected == $num){
    			$slt = ' selected';
    		}
    		$str .= '<option value="'.$num.'"'.$slt.'>'.$num.'분</option>';
    	}
    	return $str;
    }
    
    public function test()
    {
    	echo 'test ok';
    }
}

/* End of file Someclass.php */