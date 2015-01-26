<input type="radio" name="chk_info" value="1">Facebook
<input type="radio" name="chk_info" value="2">Twitter
<a href="#" class="applyBtn">나 이 곰 봤어요!</a>
<div class="popLayer" style="display: none;">
	<form id="applyForm" action="">
		<input type="text" name="idx" id="idx" value="">
		<input type="text" name="name" id="name" value="">
		<input type="text" name="appId" id="appId" value="">
		<input type="text" name="appSecret" id="appSecret" value="">
		<a href="#" class="close">X</a>
	</form>
</div>
<?php
echo "티저영상 페이지<br>";
echo '<h2>최신 이벤트 출력</h2>';
echo $this->common->print_r2($view).'<br>';
echo '<h2>라이브된 이벤트 전체 출력</h2>';
for($i=0; $i<count($blist); $i++){
	echo $this->common->print_r2($blist[$i]).'<br>';
}
echo '<h2>'.$view->name.' 이벤트 참여자</h2>';
echo $this->common->print_r2($clist).'<br>';
?>
<script type="text/javascript">
$(function(){
	var pl = $('.popLayer');
	pl.find('.close').click(function(e){
		e.preventDefault();
		$('#applyForm').each(function() {  
			this.reset();  
		});
		pl.hide();
	});
	$('.applyBtn').click(function(e){
		e.preventDefault();
		if(!$("input[name=chk_info]:checked").val())
			return;
		
		var trg = $(this),
		 sns = $("input[name=chk_info]:checked").val();
		
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("teaser/applyAction");?>',
			data: {
              "w": 'new',
              "sns": sns
			},
			success: function(data) {
				pl.find('#idx').val(data['idx']);
				pl.find('#name').val(data['name']);
				pl.find('#appId').val(data['appId']);
				pl.find('#appSecret').val(data['appSecret']);
				pl.show();
			}
		});
		
	});
});
</script>