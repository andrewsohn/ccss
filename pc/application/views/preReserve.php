<?php
	session_start();
	
	require './system/libraries/autoload.php';
	require './system/libraries/src/Twitter/TwitterOAuth.php';
	
	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\GraphUser;
	
	use Facebook\GraphSessionInfo;
	
	$id = $this->config->item('fb_id');
	$secret = $this->config->item('fb_secret');
	
	FacebookSession::setDefaultApplication($id, $secret);
	
	$helper = new FacebookRedirectLoginHelper(site_url('preReserveClose'));
	$session = $helper->getSessionFromRedirect();
	
	if(isset($session)){
		$_SESSION['token'] = $session->getToken();
		$fba = '<a href="#" data-type="1" class="applyBtn">나 이 곰 봤어요! 페이스북에 올리기</a>';
	} else {
		$scope = array('publish_actions');
		$fbahref = $helper->getLoginUrl($scope);
	}
	
	$twa = '<a href="'.site_url('twitter').'">나 이 곰 봤어요! 트위터에 올리기</a>';
	
	$time = strtotime($this->config->item('opendate_candyshop')) - strtotime(date('Y-m-d'));
	$open_date = date('d',$time);
	//$this->common->print_r2($_SESSION);
?>
<!-- container -->
<div id="container">
	<div class="hx">
		<h1>캔디크러쉬소다 사전예약 이벤트</h1>
		<button type="button" class="btn_join">사전예약 참여하기 (2015.02.12~2015.03.01)</button>
	</div>
	
	<div class="rit">
		<!-- [D] 캔디샵 오픈 : 숫자 부분 공백없이 붙여서 개발 : num0 ~ num9 -->
		<div class="dday"><span class="blind">캔디샵 오픈</span><em class="num<?php echo $open_date[0];?>"><?php echo $open_date[0];?></em><em class="num<?php echo $open_date[1];?>"><?php echo $open_date[1];?></em></div>
		<button type="button" class="btn_qus">참여방법, 경품확인</button>
	</div>
	<!-- <div class="hx">
		<h1>캔디크러쉬소다 사전예약 이벤트</h1>
		<button type="button" class="btn_join">사전예약 참여하기 (2015.02.12~2015.03.01)</button>
		<button type="button" class="btn_qus">참여방법, 경품확인</button>
	</div> -->
	
	<!-- bg 이미지 -->
	<div class="sec on"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/candyshop.jpg" alt=""></div>
	<!-- [D] 반복 -->
	<div class="sec" style="left:100%"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/candyshop2.jpg" alt=""></div>
	<div class="sec" style="left:200%"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/candyshop3.jpg" alt=""></div>
	<!-- //[D] 반복 -->
	<!-- //bg 이미지 -->
	
	<!-- [D] 줄서기 --> 
	<div class="lineup" style="width:900px">		
		<ul>
			<li class="_chrTmpl"><!-- [D] 캔디에 마우스 오버 시 말풍선 내용 보임 : li 에 .on 클래스 추가 -->
				<a href="#"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/candy1.gif" alt="캔디1"></a>
				<!-- [D] 말풍선 내용: 3줄 말줄임 처리 -->
				<div class="bx">
					<strong>abcdEfghijklmnopqrstuxyz</strong>
					ddddddddddddddddddddddddddddddddddddd3줄까지...
					<p class="sns"><span class="fb">페이스북</span> 1시간전</p>
					<button type="button" class="btn_x">닫기</button>
				</div>
				<!-- //[D] 말풍선 내용 -->
			</li>
		</ul>
	</div>
	<!-- //[D] 줄서기 -->
	
	<div class="bot">
		<div class="now">
			<span class="blind">현재까지 줄 선 인원 수</span>
			<?php
			$n1 = $n2 = $n3 = $n4 = $n5 = $n6 = $n7 = 0;
			for($i=0; $i<strlen($client_num); $i++){
				$tnum = $i+1;
				$temp = 'n'.$tnum;
				$$temp = substr($client_num,'-'.$tnum,1);
			}
			?>
			<!-- [D] 줄 선 인원 수 : num0 ~ num9 -->
			<ul>
			<li class="num<?php echo $n7?>"><?php echo $n7?></li>
			<li class="num<?php echo $n6?> numa"><?php echo $n6?></li>
			<li class="num<?php echo $n5?>"><?php echo $n5?></li>
			<li class="num<?php echo $n4?>"><?php echo $n4?></li>
			<li class="num<?php echo $n3?> numa2"><?php echo $n3?></li>
			<li class="num<?php echo $n2?>"><?php echo $n2?></li> 
			<li class="num<?php echo $n1?>"><?php echo $n1?></li>
			</ul> 
			<!-- //[D] 줄 선 인원 수 -->
		</div>
		<!-- [D] indicator : 각 버튼 활성화 .on 클래스 추가 -->
		<ul class="indicator" data-href="<?php echo site_url("preReserve/pRListAction");?>">
		<li><button type="button" class="first">맨 처음</button></li>
		<li><button type="button" class="prev">이전</button></li>
		<li><button type="button" class="my">내 위치보기</button></li>
		<li><button type="button" class="next">다음</button></li>
		<li><button type="button" class="end">맨 끝</button></li>
		</ul>
		<!-- //indicator -->
	</div>
</div>
<!-- //container -->

<!-- [D] layer -->
<div class="dimmed pink" style="display:none"></div>
<!-- layer : 사전예약 줄서기 정보 입력 -->
<div class="ly_prev" style="display:none">
	<div class="inn">
		<h1 class="blind">사전예약 참여하기</h1>
		<h2><img src="<?php echo $this->config->item('asset_url');?>/PC/img/h2.png" alt="사전예약 및 줄서기 정보 입력"></h2>
		<?php echo validation_errors(); ?>
		<?php echo form_open('', 'id="formPR" method="post"  enctype="multipart/form-data" autocomplete="off"'); ?>
		<input type="hidden" name="enc" value="">
		<div class="cont">
			<img src="<?php echo $this->config->item('asset_url');?>/PC/img/gift.png" alt="사전예약정보를 입력하시면 추첨을 통해 선물을 드려요.">
			<fieldset>
				<legend>사전예약 및 줄서기 정보 입력</legend>
				<!-- 사전 예약 -->
				<h3 class="hx_inp">사전예약 정보 입력</h3>
				<div class="bx">
					<ul class="inp">
					<li class="type _rdoWrap">
						<em class="tit">사용 기종</em>
						<span class="rdo on"><input type="radio" name="mtype" id="android" value="1" checked></span><!-- [D] checked .on 클래스 -->
						<label for="android" class="ard">안드로이드</label>
						<span class="rdo"><input type="radio" name="mtype" id="ios" value="2"></span>
						<label for="ios" class="ios">ios</label>
					</li>
					<li class="name">
						<label for="name" class="tit">이름</label>
						<span class="txt"><input type="text" name="name" id="name"></span>
					</li>
					<li class="phone">
						<em class="tit">휴대폰 번호</em>
						<div class="sel _selBox"><!-- [D] 선택 시 .on 클래스 추가 -->
							<input type="hidden" name="phNum1" title="휴대폰 첫 번호" class="_tar _num">
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
						<span class="txt"><input type="text" name="phNum2" title="휴대폰 중간 번호" class="_focusInput _num _limiter"></span>
						<span class="dash"></span>
						<span class="txt"><input type="text" name="phNum3" title="휴대폰 끝 번호" class="_focusInput _num _limiter"></span>
					</li>
					</ul>
					<ul class="info">
					<li>KING.COM INC.는 본 이벤트를 위하여 다음과 같이 고객님의 개인정보를 수집 및 이용합니다.</li>
					<li>①수집이용목적:이벤트 진행 및 경품 발송</li>
					<li>②수집항목:휴대폰 기종, 이름, 휴대폰 번호</li>
					<li>③보유/이용기간:이벤트 종료일로부터 30일간</li>
					<li>※ 수집된 개인정보는 이벤트 상품 및 게임 설치 링크 발송 용도로만 사용되며,<br><span class="blank"></span>이벤트 종료 후 30일 뒤 모두 파기됩니다.</li>
					</ul>
					<p class="agree">
						<span class="chk on"><input type="checkbox" name="agree" id="agree" value="1" class="_trg" checked></span><!-- [D] checked .on 클래스 -->
						<label class="age" for="agree">개인정보 수집 및 SMS 수신에 동의합니다.</label>
					</p>
				</div>
				<!-- //사전 예약 -->
				
				<!-- 줄서기 -->
				<h3 class="hx_cheer">사전예약 정보 입력</h3>
				<div class="bx cheer">
					<!-- 캐릭터 선택 -->
					<h4 class="hx_choice">줄서기 할 캐릭터를 선택해 주세요.</h4>
					<ul class="choice _rdoWrap">
					<li>
						<label for="candy_c1" class="cho1">캔디1</label>
						<span class="rdo on"><input type="radio" name="charIdx" id="candy_c1" value="1" checked></span>
					</li>
					<li>
						<label for="candy_c2" class="cho2">캔디2</label>
						<span class="rdo"><input type="radio" name="charIdx" id="candy_c2" value="2"></span>
					</li>
					<li>
						<label for="candy_c3" class="cho3">캔디3</label>
						<span class="rdo"><input type="radio" name="charIdx" id="candy_c3" value="3"></span>
					</li>
					<li>
						<label for="candy_c4" class="cho4">캔디4</label>
						<span class="rdo"><input type="radio" name="charIdx" id="candy_c4" value="4"></span>
					</li>
					<li>
						<label for="candy_c5" class="cho5">캔디5</label>
						<span class="rdo"><input type="radio" name="charIdx" id="candy_c5" value="5"></span>
					</li>
					<li>
						<label for="candy_c6" class="cho6">캔디6</label>
						<span class="rdo"><input type="radio" name="charIdx" id="candy_c6" value="6"></span>
					</li>
					</ul>
					<!-- //캐릭터 선택 -->
					
					<!-- 메세지 남기기 -->
					<h4 class="hx_msg">SNS 계정으로 로그인 후 응원의 메세지를 남겨주세요.</h4>
					<!-- [D] 각 선택 li .on 클래스 추가 -->
					<ul class="tab_sns">
					<input type="hidden" data-fhref="<?php echo $fbahref;?>" data-thref="<?php echo site_url('twitter/close');?>" name="snsKind" class="snsKind" value="">
					<li><a href="#" class="fb"><span>페이스북</span></a></li>
					<li><a href="#" class="tt"><span>트위터</span></a></li>
					</ul>
					<div class="inp_cheer">
						<textarea name="content" cols="30" rows="5"></textarea>
					</div>
					<!-- //메세지 남기기 -->
				</div>
				<!-- //줄서기 -->
				 
				<!-- 당첨 확인 btn -->
				<div class="btn_group">
					<button type="submit" class="btn_ok">즉시당첨 확인하기</button>
				</div>
				<!-- //당첨 확인 btn -->
			</fieldset>
			<!-- 주의 -->
			<div class="bx caution">
				<h4 class="hx_caution">주의하세요!</h4>
				<ul>
				<li>등록한 사진과 내용은 본인의 페이스북 담벼락 및 현재 페이지에 보여집니다.</li>
				<li>이벤트 당첨자는 이벤트종료 후 공식블로그를 통해 공지됩니다. (추후별도 개재)</li>
				<li>타인의 개인정보를 입력 또는 허위 정보를 입력하여 이벤트에 참여하는 경우 모든 당첨이<br>취소될 수 있습니다.</li>
				<li>작성한 글이 허위, 비방, 욕설 글로 판단될 경우 예고없이 삭제될 수 있습니다.</li>
				<li>주의사항 추가예정</li>
				</ul>
			</div>
			<!-- //주의 -->
		</div>
		</form>
		<!-- 닫기 -->
		<button class="btn_x">닫기</button>
		<!-- //닫기 -->
	</div>
</div>
<!-- //layer : 사전예약 줄서기 정보 입력 -->

<!-- layer : alert -->
<div class="dimmed pink2" style="display:none"></div>

<div class="ly_alert" style="display:none">
	<p class="name">이름을 입력해주세요</p><!-- [D] 클래스명과 p.html 내용 변경 스크립트적용 -->
	<div class="btn_group">
		<button type="button" class="btn_confirm_s">확인</button>
	</div>
</div>
<!-- //layer : alert -->

<!-- layer : 당첨 확인 -->
<div class="ly_prev" style="display:none">
	<div class="inn">
		<h1 class="blind">사전예약 참여하기</h1>
		<h2><img src="<?php echo $this->config->item('asset_url');?>/PC/img/h2_2.png" alt="당첨 결과 확인"></h2>
		<div class="cont">
			<img src="<?php echo $this->config->item('asset_url');?>/PC/img/gift_p1.jpg" alt="사전예약에 참여해주셔서 감사합니다.">
			<!-- 확인 btn -->
			<div class="btn_group">
				<button type="button" class="btn_comfirm">확인</button>
			</div>
			<!-- //확인 btn -->
			<!-- 주의 -->
			<div class="bx caution">
				<h4 class="hx_caution">주의하세요!</h4>
				<ul>
				<li>등록한 사진과 내용은 본인의 페이스북 담벼락 및 현재 페이지에 보여집니다.</li>
				<li>이벤트 당첨자는 이벤트종료 후 공식블로그를 통해 공지됩니다. (추후별도 개재)</li>
				<li>타인의 개인정보를 입력 또는 허위 정보를 입력하여 이벤트에 참여하는 경우 모든 당첨이<br>취소될 수 있습니다.</li>
				<li>작성한 글이 허위, 비방, 욕설 글로 판단될 경우 예고없이 삭제될 수 있습니다.</li>
				<li>주의사항 추가예정</li>
				</ul>
			</div>
			<!-- //주의 -->
		</div>
		<!-- 닫기 -->
		<button class="btn_x">닫기</button>
		<!-- //닫기 -->
	</div>
</div>
<!-- //당첨확인 -->
<!-- //[D] layer -->


<script type="text/javascript">
(function($, window) {
	getTextFocus = function(){
		$('textarea[name="content"]').html('');
		$('textarea[name="content"]').focus();
	}
	
	$(function() {
		var form     = $('#formPR'),
        al = $('.ly_alert'),
		pr = $('.ly_prev'),
		dimm = $('.dimmed');
		
        form.submit(function(){
			if (!form.find('input[name=name]').val()){
				al.find('p').attr('class','name').html('이름을 입력해주세요');
				al.show();
				$('.ly_prev').hide();
				return false;
			}
			
			if (!form.find('input[name=phNum1]').val() || !form.find('input[name=phNum2]').val() || !form.find('input[name=phNum3]').val()){
				al.find('p').attr('class','phone').html('휴대폰 번호를 입력해주세요');
				al.show();
				$('.ly_prev').hide();
				return false;
			}
			
			if (!form.find('input[name=agree]').is(':checked')){
				al.find('p').attr('class','pi').html('개인정보 수집에 동의해주세요');
				al.show();
				$('.ly_prev').hide();
				return false;
			}
			
			function firstAjax() {
			    return $.ajax({
					type: "POST",
					url: '<?php echo site_url("preReserve/getEncrypted");?>',
					data: {
						"name": form.find('input[name=name]').val(),
						"phNum1": form.find('input[name=phNum1]').val(),
						"phNum2": form.find('input[name=phNum2]').val(),
						"phNum3": form.find('input[name=phNum3]').val()
					},
					success: function(data){
						form.find('input[name=name]').val('');
						form.find('input[name=phNum1]').val('');
						form.find('input[name=phNum2]').val('');
						form.find('input[name=phNum3]').val('');
						form.find('input[name=enc]').val(data);
			        }
				});
			}

			function secondAjax() {
			    return $.post("<?php echo site_url('pRAction')?>", $('#formPR').serialize()).done(function(data){
				    alert(data);
			    	if(data){
				    	var res = data.split("||"),
				    	src = '<?php echo $this->config->item('asset_url');?>/PC/img/gift_p.jpg';
				    	
				    	if(res[0]){
				    		res = res[0].split(":");
				    		alert(res[1]);
				    		if(res[1]){
				    			src = '<?php echo $this->config->item('asset_url');?>/PC/img/gift_p'+res[1]+'.jpg'
					    	}
					    }

				    	form.find("input[type=text], textarea").val("");
				    	pr.eq(0).hide();
				    	dimm.eq(0).show();
				    	pr.eq(1).find('.cont > img:first').attr('src',src);
				    	pr.eq(1).show();
					}else{
						alert('등록오류입니다.\n다시 시도하여 주십시요.');
					}
				});
			}

			if(!form.find('input[name=snsKind]').val()){
				dimm.eq(1).show();
				al.find('p').attr('class','addto').html('줄서기에 같이 참여하면 당첨확률을 높일 수 있어요! 줄서기에도 참여하시겠습니까?');
				al.find('.btn_group').html('<button type="button" class="btn_yes">확인</button><button type="button" class="btn_no">아니요</button>');
				al.show();
				
				al.find('button').on('click',function(e){
					e.preventDefault();
					var ct = e.currentTarget;
					if($(ct).attr('class') == 'btn_yes'){
						dimm.eq(1).hide();
						al.hide();
						form.find('textarea[name="content"]').focus();
						return false;
					}else if($(ct).attr('class') == 'btn_no'){
						dimm.eq(1).hide();
						al.hide();
						firstAjax().success(secondAjax);
					}
				});
			}else{
				firstAjax().success(secondAjax);				
			}	

			return false;
	    });
	    
		$('.btn_confirm_s').click(function(e){
			e.preventDefault();
			var trg = $(this).closest('.ly_alert');
			trg.hide();
			pr.eq(0).show();
			if(trg.find('p').attr('class') == 'name'){
				pr.eq(0).find('input[name=name]').focus();
			} else if(trg.find('p').attr('class') == 'phone'){
				if(pr.eq(0).find('input[name=phNum2]').val())
					pr.eq(0).find('input[name=phNum3]').focus();
				else
					pr.eq(0).find('input[name=phNum2]').focus();
			} else if(trg.find('p').attr('class') == 'pi'){
				pr.eq(0).find('input[name=agree]').focus();
			}
		});
		$('.btn_comfirm').click(function(e){
			e.preventDefault();
			pr.eq(1).hide();
			location.reload();
		});
	});
})(jQuery, window);
</script>