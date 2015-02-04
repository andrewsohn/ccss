<!doctype html>
<html lang="ko">
<head>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/libs/jquery.min.js?v=<?php echo $this->config->item('js_version')?>"></script>
<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/app/common.js"></script>
</head>
<body>
<h1>폼 입력</h1>

<?php echo validation_errors(); ?>
<?php echo form_open('encrypt2', 'id="formPR" method="post"  enctype="multipart/form-data" autocomplete="off"'); ?>
	<ul class="inp">
	<li class="type _rdoWrap">
		<em class="tit">사용 기종</em>
		<span class="rdo on"><input type="radio" name="type" id="android" checked></span><!-- [D] checked .on 클래스 -->
		<label for="android" class="ard">안드로이드</label>
		<span class="rdo"><input type="radio" name="type" id="ios"></span>
		<label for="ios" class="ios">ios</label>
	</li>
	<li class="name">
		<label for="name" class="tit">이름</label>
		<span class="txt"><input type="text" name="name" id="name"></span>
	</li>
	<li class="phone">
		<em class="tit">휴대폰 번호</em>
		<div class="sel _selBox"><!-- [D] 선택 시 .on 클래스 추가 -->
			<button type="button"><span class="_text">010</span></button>
			<ul class="down _opts">
			<li data-val="010"><a href="#">010</a></li>
			<li data-val="011"><a href="#">011</a></li>
			<li data-val="016"><a href="#">016</a></li>
			<li data-val="017"><a href="#">017</a></li>
			<li data-val="018"><a href="#">018</a></li>
			<li data-val="019"><a href="#">019</a></li>
			</ul>
		</div>
		<span class="dash"></span>
		<span class="txt"><input type="text" name="ph2" title="휴대폰 중간 번호" class="_focusInput _num"></span>
		<span class="dash"></span>
		<span class="txt"><input type="text" name="ph3" title="휴대폰 끝 번호" class="_focusInput _num"></span>
	</li>
	</ul>
	<input type="submit" value="제출">
</form>
	
<div class="ly_alert" style="display:none">
<p class="name">이름</p>
<div class="btn_group">
		<button type="button" class="btn_confirm_s">확인</button>
	</div>
</div>
<script type="text/javascript">
$(function() {
	var ncont = $('.ly_alert');
	$('#formPR').submit(function(){ //문서의 모든 form이 submit될때
		console.log($(this));
		console.log($(this).find('input'));
		console.log($(this).find('input[name=name]'));
		
		if (!$(this).find('input[name=name]').val()){
			ncont.find('p').attr('class','name').html('이름');
			ncont.show();
			return false;
		}
		
		if (!$(this).find('input[name=ph2]').val()){
			ncont.find('p').attr('class','phone').html('ph2');
			ncont.show();
			return false;
		}
		
		if (!$(this).find('input[name=ph3]').val()){
			ncont.find('p').attr('class','phone').html('ph3');
			ncont.show();
			return false;
		}

		$.ajax({
			type: "POST",
			url: '<?php echo site_url("encrypt/getEncrypt");?>',
			data: {
				"name": $(this).find('input[name=name]').val(),
				"ph2": $(this).find('input[name=ph2]').val(),
				"ph3": $(this).find('input[name=ph3]').val()
			},
			success: function(data) {
				var arr = JSON.parse(data);
				arr['name']
				arr['name']
				arr['name']
			}
		});
		
		return false;
    });

	$('.btn_confirm_s').click(function(e){
		e.preventDefault();
		$(this).closest('.ly_alert').hide();
	});
});
</script>
</body>
</html>