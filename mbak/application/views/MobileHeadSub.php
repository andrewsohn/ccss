<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo $title?></title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/M/css/common.css">
<?php if($this->router->fetch_class() == 'teaser'){?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/M/css/teaser.css">
<?php }else if($this->router->fetch_class() == 'preReserve'){?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('asset_url');?>/M/css/prev.css">
<?php }?>

<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/libs/jquery.min.js?v=<?php echo $this->config->item('js_version')?>"></script>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/M/js/app/common.js"></script>
</head>
<body>