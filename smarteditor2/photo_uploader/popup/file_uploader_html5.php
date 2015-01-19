<?php
//include_once("../../../../../common.php");
define('G5_DIR_PERMISSION',  0755);
define('G5_SERVER_TIME',    time());

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

 	$sFileInfo = '';
	$headers = array();
	 
	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		} 
	}
	
	$file = new stdClass;
	//$file->name = str_replace("\0", "", rawurldecode($headers['file_name']));
    $file->name = str_replace("\0", "", rawurldecode($headers['file_name']));
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");
	
	$filename_ext = strtolower(array_pop(explode('.',$file->name)));

    if (!preg_match("/(jpe?g|gif|bmp|png)$/i", $filename_ext)) {
        echo "NOTALLOW_".$file->name;
        exit;
    }
    
	//$file_name = iconv("utf-8", "cp949", $file->name);
    $file_name = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])).'_'.get_microtime().".".$filename_ext;
    $newPath = $data_dir."/".$file_name;
    $save_url = sprintf('%s/%s', $data_url, $file_name);

    if(file_put_contents($newPath, $file->content)) {
        $sFileInfo .= "&bNewLine=true";
        $sFileInfo .= "&sFileName=".$file->name;
        $sFileInfo .= "&sFileURL=".$save_url;
    }
    
    echo $sFileInfo;
?>