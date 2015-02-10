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
	<div class="sec">
		<h1 class="blind">캔디크러시소다 사전예약 이벤트</h1>
		<button class="btn_pp join"><span>사전예약 참여하기</span></button>
		<div class="count">
			<em class="blind">현재까지 줄 선 인원</em>
			<?php
			$n1 = $n2 = $n3 = $n4 = $n5 = $n6 = $n7 = 0;
			for($i=0; $i<strlen($client_num); $i++){
				$tnum = $i+1;
				$temp = 'n'.$tnum;
				$$temp = substr($client_num,'-'.$tnum,1);
			}
			?>
			<!-- 줄 선 인원 -->
			<ul class="now">
			<li class="num<?php echo $n7?>"><?php echo $n7?></li>
			<li class="num<?php echo $n6?>"><?php echo $n6?></li>
			<li class="num<?php echo $n5?>"><?php echo $n5?></li>
			<li class="num<?php echo $n4?>"><?php echo $n4?></li>
			<li class="num<?php echo $n3?>"><?php echo $n3?></li>
			<li class="num<?php echo $n2?>"><?php echo $n2?></li>
			<li class="num<?php echo $n1?>"><?php echo $n1?></li>
			</ul>
			<!-- //줄 선 인원 -->
			<em class="blind">캔디샵 오픈 디데이</em>
			<!-- 캔디샵 디데이 -->
			<ul class="dday">
			<li class="num<?php echo $open_date[0];?>"><?php echo $open_date[0];?></li>
			<li class="num<?php echo $open_date[1];?>"><?php echo $open_date[1];?></li>
			</ul>
			<!-- //캔디샵 디데이 -->
		</div>
	</div>
	
	<div class="sec2">
		<div class="blind">
			사전예약정보를 입력하시면 추첨을 통해 선물을 드려요~
			캔디크러시소다 하트 100개 : 전원 / 외식 상품권 5만원 : 5명
		</div>
	</div>
	<?php echo validation_errors(); ?>
		<?php echo form_open('', 'id="formPR" method="post"  enctype="multipart/form-data" autocomplete="off"'); ?>
		<input type="hidden" name="enc" value="">
	<fieldset class="sec3">
		<legend>사전예약 정보 입력</legend>
		<div class="inn">
			<h2 class="hx_pp"><span class="hx_prev">사전예약 정보 입력</span></h2>
			<!-- 정보 입력 -->
			<ul class="inp">
			<li class="_rdoWrap">
				<em class="use">사용기종</em>
				<input type="radio" name="mtype" id="android" value="1" checked><label for="android" class="ard">안드로이드</label>
				<input type="radio" name="mtype" id="ios" value="2"><label for="ios" class="ios">ios</label>
			</li>
			<li>
				<em class="name">이름</em>
				<input type="text" name="name" title="이름">
			</li>
			<li>
				<em class="phone">휴대폰번호</em>
				<select name="phNum1" id="phNum1" title="휴대폰 국번">
				<option value="010">010</option>
				<option value="011">011</option>
				<option value="016">016</option>
				<option value="017">017</option>
				<option value="018">018</option>
				<option value="019">019</option>
				</select>
				<span class="dash"></span>
				<input type="tel" name="phNum2" title="휴대폰 중간 번호" class="_focusInput _num _limiter">
				<span class="dash"></span>
				<input type="tel" name="phNum3" title="휴대폰 마지막 번호" class="_focusInput _num _limiter">
			</li>
			</ul>
			<!-- //정보 입력 -->
			<ul class="info">
			<li>KING.COM INC.는 본 이벤트를 위하여 다음과 같이 고객님의 개인정보를 수집 및 이용합니다.</li>
			<li>① 수집이용목적:이벤트 진행 및 경품 발송</li>
			<li>② 수집항목:휴대폰 기종, 이름, 휴대폰 번호</li>
			<li>③ 보유/이용기간:이벤트 종료일로부터 30일간</li>
			<li>수집된 개인정보는 이벤트 상품 및 게임 설치 링크 발송 용도로만 사용되며, 이벤트 종료 후 30일 뒤 모두 파기됩니다.</li>
			</ul>
			<!-- 동의 -->
			<p class="agree">
				<input type="checkbox" name="agree" id="agree" value="1" checked>
				<label class="age" for="agree">개인정보 수집 및 SMS 수신에 동의합니다.</label>
			</p>
			<!-- //동의 -->
		</div>
	</fieldset>
	
	<fieldset class="sec4">
		<legend>줄서기 정보 입력</legend>
		<div class="inn">
			<h2 class="hx_pp"><span class="hx_lineup">줄서기 정보 입력</span></h2>
			<h3>줄서기 할 캐릭터를 선택해주세요.</h3>
			<!-- 캐릭터 선택 -->
			<ul class="choice">
			<li>
				<label for="candy_c1" class="cho1">캔디1</label>
				<input type="radio" name="charIdx" id="candy_c1" value="1" checked>
			</li>
			<li>
				<label for="candy_c2" class="cho2">캔디2</label>
				<input type="radio" name="charIdx" id="candy_c2" value="2">
			</li>
			<li>
				<label for="candy_c3" class="cho3">캔디3</label>
				<input type="radio" name="charIdx" id="candy_c3" value="3">
			</li>
			<li>
				<label for="candy_c4" class="cho4">캔디4</label>
				<input type="radio" name="charIdx" id="candy_c4" value="4">
			</li>
			<li>
				<label for="candy_c5" class="cho5">캔디5</label>
				<input type="radio" name="charIdx" id="candy_c5" value="5">
			</li>
			<li>
				<label for="candy_c6" class="cho6">캔디6</label>
				<input type="radio" name="charIdx" id="candy_c6" value="6">
			</li>
			</ul>
			<!-- //캐릭터 선택 -->
			<!-- 응원 글 쓰기 -->
			<div class="inp_cheer">
				<ul class="tab_sns">
				<input type="hidden" data-fhref="<?php echo $fbahref;?>" data-thref="<?php echo site_url('twitter/close');?>" name="snsKind" class="snsKind" value="">
				<li><a href="#" class="fb">페이스북</a></li>
				<li><a href="#" class="tt">트위터</a></li>
				</ul>
				<textarea name="content" cols="30" rows="5">좌측에 SNS 계정으로 로그인하시면 응원의 메세지를 작성하실 수 있습니다.</textarea>
			</div>
			<!-- //응원 글 쓰기 -->
			<button type="submit" class="btn_pp confirm"><span>즉시당첨 확인하기</span></button>
		</div>
	</fieldset>
	</form>
	<div class="sec5">
		<div class="inn">
			<!-- 줄서기 리스트 -->
			<ul class="lineup">
			<?php for($i=0;$i<count($clist);$i++){?>
			<li>
				<em class="cho<?php echo $clist[$i]->charIdx?>">캔디<?php echo $clist[$i]->charIdx?></em>
				<strong><?php echo $clist[$i]->vname?></strong>
				<p class="txt"><?php echo $this->common->getShortenText($clist[$i]->content); ?></p>
				<span class="<?php if($clist[$i]->type == 1){ echo 'fb'; }else if($clist[$i]->type == 2){ echo 'tt'; } ?>"><?php echo $this->common->getTime($clist[$i]->registDt);?></span>
			</li>
			<?php }?>
			</ul>
			<!-- //줄서기 리스트 -->
			<?php
			if(count($clist)){ 
			?>
			<button type="button" data-last="<?php echo $clist[count($clist)-1]->idx?>" class="btn_pp more"><span>더보기</span></button>
			<?php }?>
			
		</div>
	</div>
	<!-- 기타 링크 -->
	<div class="sec6">
		<ul>
		<li><a href="#" class="mov _construct">영상갤러리</a></li>
		<li><a href="#" class="blog _construct">KING 공식 블로그</a></li>
		<li><a href="#" class="fb _construct">캔디크러시소다 공식 페이스북</a></li>
		</ul>
	</div>
	<!-- //기타 링크 -->
</div>
<!-- //container -->

<script type="text/javascript">

(function($, window) {
	getTextFocus = function(){
		$('textarea[name="content"]').html('');
		$('textarea[name="content"]').focus();
	}
	$(function() {
		var l = $('#characters'),
		t = $('#target');

		$.ajax({
			type: "POST",
			url: '<?php echo site_url("preReserve/pRListAction");?>',
			data: {
				"idx": '0',
				"size": '5'
			},
			success: function(data) {
				if(data){
					res = data.split("|||");
					for(i=0; i<res.length; i++){
						if(i == 0){
							l.append(res[i]);
						}else if(i == 1){
							t.val(res[i]);
						}
					}
				}
			}
		});

		//<,<<,내위치보기,>>,> 버튼
		$('.btn_apply').click(function(e){
			e.preventDefault();

			var mode = $(this).attr('data-mode');
			
			if(mode == 'my'){
				if($(this).attr('data-type') == 'fb'){
					<?php if(isset($session)){?>
					alert(1);
					//팝업레이어 부름
					<?php }else{?>
					location.replace('<?php echo $fbahref ?>');
					<?php }?>
				}else if($(this).attr('data-type') == 'tt'){
					<?php if(isset($_SESSION['oauth_token']) && isset($_REQUEST['oauth_verifier'])){?>
					alert(2);
					//팝업레이어 부름
					<?php }else{?>
					location.replace('<?php echo site_url('twitter/preReserve') ?>');
					<?php }?>
				}
				
			}else{
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("preReserve/pRListAction");?>',
					data: {
					  "mode": mode,
					  "idx": t.val(),
					  "size": '5'
					},
					success: function(data) {
						alert(data);
						if(data){
							res = data.split("|||");
							for(i=0; i<res.length; i++){
								if(i == 0){
									l.append(res[i]);
								}else if(i == 1){
									t.val(res[i]);
								}
							}
						}
					}
				});
			}
		});

		var form     = $('#formPR'),
        al = $('.ly_alert:not(.gift)'),
		pr = $('.ly_prev'),
		dimm = $('.dimmed');
		form.find('select[name=phNum1]').val();
		
        form.submit(function(){
			if (!form.find('input[name=name]').val()){
				al.find('p').attr('class','name').html('이름을 입력해주세요');
				al.show();
				dimm.show();
				return false;
			}
			
			if (!form.find('#phNum1').val() || !form.find('input[name=phNum2]').val() || !form.find('input[name=phNum3]').val()){
				al.find('p').attr('class','phone').html('휴대폰 번호를 입력해주세요');
				al.show();
				dimm.show();
				return false;
			}
			
			if (!form.find('input[name=agree]').is(':checked')){
				al.find('p').attr('class','pi').html('개인정보 수집에 동의해주세요');
				al.show();
				dimm.show();
				return false;
			}

			function firstAjax() {
			    return $.ajax({
					type: "POST",
					url: '<?php echo site_url("preReserve/getEncrypted");?>',
					data: {
						"name": form.find('input[name=name]').val(),
						"phNum1": form.find('select[name=phNum1]').val(),
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
			    return $.post("<?php echo site_url('mPRAction')?>", $('#formPR').serialize()).done(function(data){
			    	if(data){
				    	var res = data.split("||"),
				    	src = '<?php echo $this->config->item('asset_url');?>/M/img/gift_p.jpg';
				    	
				    	if(res[0]){
				    		res = res[0].split(":");
				    		if(res[1]){
				    			src = '<?php echo $this->config->item('asset_url');?>/M/img/gift_p'+res[1]+'.jpg'
					    	}
					    }

				    	form.find("input[type=text], textarea").val("");
				    	$('.ly_alert:has(.gift)').find('img:first').attr('src',src);
				    	dimm.show();
				    	$('.ly_alert:has(.gift)').show();
					}else{
						alert('등록오류입니다.\n다시 시도하여 주십시요.');
					}
				});
			}
			
			if(!form.find('input[name=snsKind]').val()){
				dimm.show();
				al.find('p').attr('class','addto').html('줄서기에 같이 참여하면 당첨확률을 높일 수 있어요! 줄서기에도 참여하시겠습니까?');
				al.find('.btn_group').html('<button type="button" class="btn_yes">확인</button><button type="button" class="btn_no">아니요</button>');
				al.show();
				
				al.find('button').on('click',function(e){
					e.preventDefault();
					var ct = e.currentTarget;
					if($(ct).attr('class') == 'btn_yes'){
						dimm.hide();
						al.hide();
						form.find('textarea[name="content"]').focus();
						return false;
					}else if($(ct).attr('class') == 'btn_no'){
						dimm.hide();
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
			dimm.hide();
			if(trg.hasClass('gift')){
				location.reload();
			}else{
				if(trg.find('p').attr('class') == 'name'){
					form.find('input[name=name]').focus();
				} else if(trg.find('p').attr('class') == 'phone'){
					if(form.find('input[name=phNum2]').val())
						form.find('input[name=phNum3]').focus();
					else
						form.find('input[name=phNum2]').focus();
				} else if(trg.find('p').attr('class') == 'pi'){
					form.find('input[name=agree]').focus();
				}
			}
		});

		$('.more').click(function(e){
			e.preventDefault();
			var $this= $(this),
			 $trg = $('.lineup'),
			 last = $this.attr('data-last');
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("m/getMoreListPRMob");?>',
				data: {
	             "idx": last
				},
				success: function(data) {
					if(data){
						res = data.split("|||");
						for(i=0; i<res.length; i++){
							if(i == 0){
								$trg.append(res[i]);
							}else if(i == 1){
								$this.attr('data-last',res[i]);
							}
						}
					}
				},
			    error:function(e){ 
			    }
			});
		});
		
		$('.join').click(function(e){
			e.preventDefault();
		    $("html, body").animate({ scrollTop: $('.sec3').position().top }, "slow");
		    form.find('input[name=name]').focus();
		});
	});
})(jQuery, window);
</script>