<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo $title?></title>
<link rel="shortcut icon" href="<?php echo $this->config->item('asset_url');?>/PC/img/favicons.png">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/M/css/common.css">
<?php if($this->router->fetch_class() == 'teaser'){?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/M/css/teaser.css">
<?php }else if($this->router->fetch_class() == 'preReserve'){?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/M/css/prev.css">
<?php }?>

<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/libs/jquery.min.js?v=<?php echo $this->config->item('js_version')?>"></script>
<?php if($this->router->fetch_class() == 'teaser'){?>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/M/js/app/teaser.js?v<?php echo $this->config->item('js_version')?>"></script>
<?php }else if($this->router->fetch_class() == 'preReserve'){?>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/M/js/app/preReserve.js"></script>
<?php }?>
<meta property="og:title" content="곰나 귀여운 녀석들이 온다곰!" charset="utf-8">
<meta property="og:description" content="캔디크러쉬소다와 함께 찾아온 소다곰 가족의 좌충우돌 적응기!! 지금 사전 예약도 참여하고 영상도 확인하세요! (2015년 2월 12일 ~ 3월 1일)" charset="utf-8">
<meta property="og:image" content="<?php echo $this->config->item('asset_url');?>/PC/img/share_thumb.jpg">
</head>
<body>