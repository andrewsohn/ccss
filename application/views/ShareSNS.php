<?php
echo "SNS 공유하기<br>";
echo '<h2>'.$cidx.'</h2>';
//echo $this->common->print_r2($clist).'<br>';
?>
<a href="<?php echo site_url('ShareSNS/Facebook/'.$cidx)?>">페이스북</a>
<a href="<?php echo site_url('ShareSNS/Twitter/'.$cidx)?>">트위터</a>

<a href="#" class="wClose">닫기</a>
<script>
$(function(){
	$('.wClose').click(function(e){
		e.preventDefault();
		var daddy = window.self;
		daddy.opener = window.self;
		daddy.close();
	});
});
</script>