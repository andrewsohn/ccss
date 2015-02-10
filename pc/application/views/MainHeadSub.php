<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
<title><?php echo $title?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/PC/css/common.css?v<?php echo $this->config->item('css_version')?>">
<?php if($this->router->fetch_class() == 'teaser'){?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/PC/css/teaser.css?v<?php echo $this->config->item('css_version')?>">
<?php }else if($this->router->fetch_class() == 'preReserve'){?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/PC/css/prev.css?v<?php echo $this->config->item('css_version')?>">
<?php }?>

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
</script>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/libs/jquery.min.js?v<?php echo $this->config->item('js_version')?>"></script>
<?php if($this->router->fetch_class() == 'teaser'){?>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/app/teaser.js?v<?php echo $this->config->item('js_version')?>"></script>
<?php }else if($this->router->fetch_class() == 'preReserve'){?>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/libs/jquery.tmpl.min.js?v<?php echo $this->config->item('js_version')?>"></script>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/app/preReserve.js?v<?php echo $this->config->item('js_version')?>"></script>
<?php }?>

</head>
<body>