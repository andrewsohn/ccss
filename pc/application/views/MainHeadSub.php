<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
<title><?php echo $title?></title>
<link rel="shortcut icon" href="<?php echo $this->config->item('asset_url');?>/PC/img/favicons.png">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/PC/css/common.css?v<?php echo $this->config->item('css_version')?>">
<?php if($this->router->fetch_class() == 'teaser'){?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/PC/css/teaser.css?v<?php echo $this->config->item('css_version')?>">
<?php }else if($this->router->fetch_class() == 'preReserve'){?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/PC/css/prev.css?v<?php echo $this->config->item('css_version')?>">
<?php }?>

<!--[if lte IE 8]>
<script src="http://www.candycrushsoda.co.kr/assets/PC/js/libs/html5.js"></script>
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
<meta property="og:title" content="곰나 귀여운 녀석들이 온다곰!" charset="utf-8">
<meta property="og:description" content="캔디크러쉬소다와 함께 찾아온 소다곰 가족의 좌충우돌 적응기!! 지금 사전 예약도 참여하고 영상도 확인하세요! (2015년 2월 12일 ~ 3월 1일)" charset="utf-8">
<meta property="og:image" content="<?php echo $this->config->item('asset_url');?>/PC/img/share_thumb.jpg">
</head>
<body>
<p class="skip"><a href="#container">이벤트 본문 바로가기</a></p>