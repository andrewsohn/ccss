<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
<title>곰가족의 반란 CCSS</title>
<link rel="stylesheet" type="text/css" href="http://www.candycrushsoda.co.kr/assets/PC/css/common.css">
<link rel="stylesheet" type="text/css" href="http://www.candycrushsoda.co.kr/assets/PC/css/prev.css">

<!--[if lte IE 8]>
<script src="http://ccss.hivelab.co.kr/test_site/js/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="http://www.candycrushsoda.co.kr/assets/PC/js/libs/jquery.min.js?v=1.11.2"></script>
<script type="text/javascript" src="http://www.candycrushsoda.co.kr/assets/PC/js/app/preReserve.js"></script>
</head>
<body>
<form method="post">
	<input type="text" name="w1" value="">
	<input type="text" name="w2" value="">
	<input type="text" name="w3" value="">
		<textarea name="bf_content" id="bf_content" cols="10" rows="5"></textarea>
		<div class="btn_group">
			<button type="submit" class="btn_regist">등록</button>
			<button type="button" class="btn_cancel">취소</button>
		</div>
	</form>
<script>
(function($, window) {
	$(function() {
		var form     = $('form');
		
        form.submit(function(){
        	$.post("<?php echo site_url('test/action')?>", form.serialize()).done(function(data){
			    alert(data);
			});
			return false;
        });
	});
})(jQuery, window);
</script>
</body>
</html>