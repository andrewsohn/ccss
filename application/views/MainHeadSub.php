<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
<title><?php echo $title?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/PC/css/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/PC/css/teaser.css">
<!--[if lte IE 8]>
<script src="http://ccss.hivelab.co.kr/test_site/js/html5.js"></script>
<![endif]-->
<script>
openMainShare = function(a){
	 var href = "<?php echo site_url('ShareSNS').'?sns='?>"+$(a).attr('class')
	 ,new_win = window.open(href, 'win_main_share', 'left=100,top=100,width=600,height=580,scrollbars=0');
	new_win.focus();
	return false;
}

//자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "http://ccss.hivelab.co.kr/ccss";
var g5_is_member = "";
var g5_is_admin  = "";
var g5_is_mobile = "";
var g5_bo_table  = "";
var g5_sca       = "";
var g5_editor    = "";
var g5_cookie_domain = "";
</script>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/admin/js/lib/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/admin/js/app/common.js"></script>
</head>
<body>