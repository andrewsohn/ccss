<?php
//include_once("../../../../../common.php");
define('G5_DIR_PERMISSION',  0755);
define('G5_SERVER_TIME',    time());
// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

$ym = date('ym', G5_SERVER_TIME);

$result['path'] = str_replace('\\', '/', dirname(__FILE__));
$tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
$document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
$root = str_replace($document_root, '', $result['path']);
$port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
$http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
$user = str_replace(str_replace($document_root, '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']);
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
	$host = preg_replace('/:[0-9]+$/', '', $host);
$result['url'] = $http.$host.$port.$user.$root;


$data_dir = $result['path'].'/data/editor/'.$ym;
$data_url = $result['url'].'/data/editor/'.$ym;

@mkdir($data_dir, G5_DIR_PERMISSION);
@chmod($data_dir, G5_DIR_PERMISSION);

// SUCCESSFUL
if(bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	
	$filename_ext = strtolower(array_pop(explode('.',$name)));
	
	if (!preg_match("/(jpe?g|gif|bmp|png)$/i", $filename_ext)) {
		$url .= '&errstr='.$name;
	} else {
		
        $file_name = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])).'_'.get_microtime().".".$filename_ext;
		$save_dir = sprintf('%s/%s', $data_dir, $file_name);
        $save_url = sprintf('%s/%s', $data_url, $file_name);
		
		@move_uploaded_file($tmp_name, $save_dir);
		
		$url .= "&bNewLine=true";
		$url .= "&sFileName=".$name;
		$url .= "&sFileURL=".$save_url;
	}
}
// FAILED
else {
	$url .= '&errstr=error';
}
	
header('Location: '. $url);
?>