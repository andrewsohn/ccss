<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/libs/facebook_en_US.js"></script>
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
	$open_date = date('d', $time);
	
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
		<div class="dday _openDt"><span class="blind">캔디샵 오픈</span><em class="num9 _num">9</em><em class="num9 _num">9</em></div>
		<button type="button" class="btn_qus">참여방법, 경품확인</button>
	</div>
	
	<!-- bg 이미지 -->
	<div class="_slideWrap">
		<div class="sec first on _slide"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/candyshop.jpg" alt=""></div>
		<!-- [D] 반복 -->
		<div class="sec _slide 1" style="left:100%"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/candyshop2.jpg" alt=""></div>
		<div class="sec _slide 2" style="left:200%"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/candyshop3.jpg" alt=""></div>		
	</div>	
	<!-- //[D] 반복 -->
	<!-- //bg 이미지 -->
	
	<!-- [D] 줄서기 --> 
	<div class="lineup _lineupChr" style="width:900px">		
		<ul class="_list">
		<script class="_tmpl" type="text/x-jquery-tmpl">
		{{if !window.CCSS.Util.isArray(data) || !data.length }}
			<li></li>
		{{else}}
			{{each(i, value) data}}
			<li class="_item${value.idx}">
				<a href="#"><img src="<?= $this->config->item('asset_url'); ?>/PC/img/candy${value.chrIdx}.gif" alt="캔디${value.chrIdx}"></a>
				<div class="bx">
					<strong>${value.name}</strong>
					${value.content}
					<p class="sns"><span class="${value.typeCss}">${value.typeName}</span>${value.regDt}</p>
					<button type="button" class="btn_x _close">닫기</button>
				</div>
				<!-- //[D] 말풍선 내용 -->
			</li>
			{{/each}}
		{{/if}}
		</script>
		</ul>
	</div>
	<!-- //[D] 줄서기 -->
	
	<div class="bot _lineupNavi">
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
				<li class="_num num<?php echo $n7?>"><?php echo $n7?></li>
				<li class="_num num<?php echo $n6?> numa"><?php echo $n6?></li>
				<li class="_num num<?php echo $n5?>"><?php echo $n5?></li>
				<li class="_num num<?php echo $n4?>"><?php echo $n4?></li>
				<li class="_num num<?php echo $n3?> numa2"><?php echo $n3?></li>
				<li class="_num num<?php echo $n2?>"><?php echo $n2?></li> 
				<li class="_num num<?php echo $n1?>"><?php echo $n1?></li>
			</ul> 
			<!-- //[D] 줄 선 인원 수 -->
		</div>
		<!-- [D] indicator : 각 버튼 활성화 .on 클래스 추가 -->
				
<!-- 		<ul class="indicator _pagination"> -->
<!-- 		<li><button type="button" class="first">맨 처음</button></li> -->
<!-- 		<li><button type="button" class="prev">이전</button></li> -->
<!-- 		<li><button type="button" class="my">내 위치보기</button></li> -->
<!-- 		<li><button type="button" class="next">다음</button></li> -->
<!-- 		<li><button type="button" class="end">맨 끝</button></li> -->
<!-- 		</ul> -->
		
		<div class="indicator v2 _pagination">
			<button type="button" class="my">내 위치보기</button>
		</div>
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
			<img src="<?php echo $this->config->item('asset_url');?>/PC/img/gift.jpg" alt="사전예약정보를 입력하시면 추첨을 통해 선물을 드려요.">
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
					<li>- King은 본 이벤트 진행 및 경품 발송을 위해 귀하의 휴대전화 번호를 수집 및 이용합니다.</li>
					<li>- King은 경품 발송 등 본 이벤트에 관한 업무를 (주) HS Ad에게 위탁하여 운영하고자 합니다.</li>
					<li>- 개인정보의 수집 및 이용 등 처리에 관한 동의를 거부할 권리가 있습니다.</li>
					<li>- 입력된 정보는 (주) HS Ad에서 수집하여 본 이벤트를 위해 King으로 전달되며, 사전 예약 참여 후<br><span class="blank"></span>개인정보 (이름/휴대폰 번호/휴대폰 기종)는 경품 발송 용도로 필요한 기간 동안(이벤트 종료 후 30일)만<br><span class="blank"></span>이용된 후 모든 정보는 즉시 파기됩니다.</li>
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
						<textarea name="content" cols="30" rows="5" title="응원 메세지 쓰기"></textarea>
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
				<li>모든 경품의 제세공과금은 King에서 부담합니다.</li>
				<li>경품은 사정에 따라 변경될 수 있으며 경품 이미지는 실제와 다를 수 있습니다.</li>
				<li>당첨된 경품 외, 다른 제품으로 변경이 불가합니다.</li>
				<li>본 이벤트 경품 당첨자는 추첨을 통해 선정되며,<br>당첨 공지는 각 이벤트에 명시되어 있는 당첨자 발표일에 <a href="http://www.candycrushsoda.co.kr/blog" target="_blank">King 공식 블로그</a>에서 발표됩니다.<br>
				(단, 이벤트 안내 사항에 동의하지 않거나, 타인의 휴대전화 정보 또는 허위 정보를 입력하여<br>이벤트에 참여하는 경우 모든 당첨이 취소될 수 있습니다.)</li>
				<li>전 예약은 하나의 핸드폰 번호로만 참여 하실 수 있습니다.</li>
				<li>기타 부정한 방법으로 인한 당첨 시 당첨이 취소됩니다.</li>
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
		<button type="button" class="btn_confirm_s _preReserveConfirm">확인</button>
	</div>
</div>

<div class="ly_alert" style="display:none">
	<p class="sns">사용하시는 SNS를 선택해주세요</p>
	<div class="btn_group">
		<button type="button" class="btn_fb">페이스북</button>
		<button type="button" class="btn_tt">트위터</button>
	</div>
</div>

<!-- 이미참여 -->
<div class="ly_alert" style="display:none">
	<p class="aleady">이미 참여하셨습니다.</p>
	<div class="btn_group">
		<button type="button" class="btn_confirm_s">확인</button>
	</div>
</div>
<!-- //이미참여 -->

<!-- 페이스북 담벼락 쓰기 -->
<div class="ly_alert" style="display:none">
	<p class="addfb">해당 내용을 페이스북에 공유하셔야 이벤트 참여가 완료됩니다.</p>
	<div class="btn_group">
		<button type="button" class="btn_fb2">페이스북 공유</button>
	</div>
</div>
<!-- //페이스북 담벼락 쓰기 -->
<!-- //layer : alert -->

<!-- 본인위치 -->
<div class="ly_alert _alertMyPosition" style="display:none">
	<p class="now">현재 본인의 위치입니다.</p>
	<div class="btn_group">
		<button type="button">확인</button>
	</div>
</div>
<!-- //본인위치 -->


<!-- layer : 당첨 확인 -->
<div class="ly_prev" style="display:none">
	<div class="inn">
		<h1 class="blind">사전예약 참여하기</h1>
		<h2><img src="<?php echo $this->config->item('asset_url');?>/PC/img/h2_2.png" alt="당첨 결과 확인"></h2>
		<div class="cont">
			<img src="<?php echo $this->config->item('asset_url');?>/PC/img/gift_p.jpg" alt="사전예약에 참여해주셔서 감사합니다.">
			<div class="btn_group">
				<button type="button" class="btn_comfirm">확인</button>
			</div>
			<!-- //확인 btn -->
			<!-- 주의 -->
			<div class="bx caution">
				<h4 class="hx_caution">주의하세요!</h4>
				<ul>
				<li>모든 경품의 제세공과금은 King에서 부담합니다.</li>
				<li>경품은 사정에 따라 변경될 수 있으며 경품 이미지는 실제와 다를 수 있습니다.</li>
				<li>당첨된 경품 외, 다른 제품으로 변경이 불가합니다.</li>
				<li>본 이벤트 경품 당첨자는 추첨을 통해 선정되며,<br>당첨 공지는 각 이벤트에 명시되어 있는 당첨자 발표일에 <a href="http://www.candycrushsoda.co.kr/blog" target="_blank">King 공식 블로그</a>에서 발표됩니다.<br>(단, 이벤트 안내 사항에 동의하지 않거나, 타인의 휴대전화 정보 또는 허위 정보를 입력하여<br>이벤트에 참여하는 경우 모든 당첨이 취소될 수 있습니다.)</li>
				<li>기타 부정한 방법으로 인한 당첨 시 당첨이 취소됩니다.</li>
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

<!-- layer : 참여방법 및 경품확인 -->
	<div class="ly_prev v2" style="display:none;">
		<div class="inn">
			<h1 class="blind">참여방법 및 경품확인</h1>
			<div class="cont">
				<img src="<?php echo $this->config->item('asset_url');?>/PC/img/howto.jpg" alt="">
				<ul class="blind">
				<li>1. 사전예약 참여하기 버튼을 누릅니다.</li>
				<li>2. 사전예약 정보를 입력합니다.(사용기종, 이름, 휴대폰 번호)</li>
				<li>3. 줄서기에 사용할 캐릭터를 선택합니다.</li>
				<li>4. 응원의 글을 입력하고 신청버튼을 누르면 참여완료!</li>
				</ul>
				<button type="button" class="btn_go">사전예약 및 줄서기이벤트에 지금 바로 참여하세요. 2015.02.12~2015.03.01</button>
				<img src="<?php echo $this->config->item('asset_url');?>/PC/img/info_gift.jpg" alt="즉시 당첨 경품: 아이패드미니, 스타벅스 아메리카노 기프티콘, 던킨 상품권 1만원권, 베스킨라빈스 상품권 1만원권, 구글 기프트카드 1만원, 3만원, 5만원권, 캔디크러쉬소다 에코백, 마이보틀, 커플컵, 노트, 볼펜, 티셔츠, 스티커">
				<!-- 주의 -->
				<div class="bx caution">
					<h4 class="hx_caution">주의하세요!</h4>
					<ul>
					<li>모든 경품의 제세공과금은 King에서 부담합니다.</li>
					<li>경품은 사정에 따라 변경될 수 있으며 경품 이미지는 실제와 다를 수 있습니다.</li>
					<li>당첨된 경품 외, 다른 제품으로 변경이 불가합니다.</li>
					<li>본 이벤트 경품 당첨자는 추첨을 통해 선정되며,<br>당첨 공지는 각 이벤트에 명시되어 있는 당첨자 발표일에 <a href="http://www.candycrushsoda.co.kr/blog" target="_blank">King 공식 블로그</a>에서 발표됩니다.<br>(단, 이벤트 안내 사항에 동의하지 않거나, 타인의 휴대전화 정보 또는 허위 정보를 입력하여<br>이벤트에 참여하는 경우 모든 당첨이 취소될 수 있습니다.)</li>
					<li>기타 부정한 방법으로 인한 당첨 시 당첨이 취소됩니다.</li>
					</ul>
				</div>
				<!-- //주의 -->
			</div>
			<!-- 닫기 -->
			<button class="btn_x">닫기</button>
			<!-- //닫기 -->
		</div>
	</div>
	<!-- //참여방법 및 경품확인 -->
<!-- //[D] layer -->


<script type="text/javascript">
(function($, window) {
	$(function() {
		$.ajaxSetup({ cache: false });
		FB.init({
	      appId: '<?php echo $this->config->item('fb_id');?>',
	    });

		var preReserveUI;
		if (window.CCSS && window.CCSS.UI && window.CCSS.UI.PreReserve) {
			preReserveUI = window.CCSS.UI.PreReserve.init($('body'), { 
				actionUrl : "<?= site_url("preReserve/pRListAction"); ?>"
				, assetUrl : '<?= $this->config->item('asset_url'); ?>' 
			});
		}

		window.getTextFocus = function(snsType){
			if($('.ly_alert').eq(1).css('display') == 'none'){
				$('textarea[name="content"]').html('');
				$('textarea[name="content"]').focus();
			}else{
				$('.dimmed').hide();
				$('.ly_alert').eq(1).hide();
				
				//내위치로 이동 animation 구현
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("pRAction/getMyPos");?>',
					data: {
						"snsType": snsType
					},
					success: function(data){
						if (!preReserveUI || null == data) return;
						
						preReserveUI.moveMyPosition(data);
			        }
				});
				
			}
		}
		
		var form     = $('#formPR'),
        al = $('.ly_alert'),
		pr = $('.ly_prev'),
		dimm = $('.dimmed');
		
        form.submit(function(){
        	al.eq(0).find('.btn_group > button').eq(0).attr('class','btn_confirm_s _preReserveConfirm');
        	al.eq(0).find('.btn_group > button').eq(1).remove();
			if (!form.find('input[name=name]').val()){
				al.eq(0).find('p').attr('class','name').html('이름을 입력해주세요');
				
				al.eq(0).show();
				$('.ly_prev').hide();
				return false;
			}
			
			if (!form.find('input[name=phNum1]').val() || !form.find('input[name=phNum2]').val() || !form.find('input[name=phNum3]').val()){
				al.eq(0).find('p').attr('class','phone').html('휴대폰 번호를 입력해주세요');
				al.eq(0).show();
				$('.ly_prev').hide();
				return false;
			}

			if (!form.find('input[name=agree]').is(':checked')){
				al.eq(0).find('p').attr('class','pi').html('개인정보 수집에 동의해주세요');
				al.eq(0).show();
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
						form.find('input[name=phNum2]').val('');
						form.find('input[name=phNum3]').val('');
						form.find('input[name=enc]').val(data);
			        }
				});
			}

			function secondAjax() {
				if(form.find('input[name=snsKind]').val() == 'fb'){
					al.eq(3).show();
					al.eq(3).find('.btn_fb2').on('click',function(e){
						al.eq(3).hide();
						e.preventDefault();
						FB.ui({
					    	  method: 'feed',
					    	  link: '<?php echo site_url('preReserve')?>',
					    	  picture: '<?php echo $this->config->item('asset_url');?>/PC/img/fb_thum.jpg',
					    	  description: form.find('textarea').html()
					    	}, function(response){
					    		if (response && !response.error_code) {
					    			$.post("<?php echo site_url('pRAction')?>", $('#formPR').serialize()).done(function(data){
					    				if(data != 0 && data != 1){
						    				var res = data.split("||"),
									    	src = '<?php echo $this->config->item('asset_url');?>/PC/img/gift_p.jpg',
									    	sns = res[2].split(":")[1],
									    	alt = '사전예약에 참여해주셔서 아쉽게도 당첨되지 않으셨네요. 꽝. 사전예약 이후에도 많은 관심 부탁드립니다.';
									    	
									    	var idx = res[1].split(":")[1];
									    	
									    	if(res[0]){
									    		res = res[0].split(":");
									    		if(res[1]){
									    			src = '<?php echo $this->config->item('asset_url');?>/PC/img/gift_p'+res[1]+'.jpg'

									    			switch(res[1]) {
									    		    case 1:
									    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 티셔츠에 당첨되셨습니다.';
									    		        break;
									    		    case 2:
									    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 볼펜에 당첨되셨습니다.';
									    		        break;
									    		    case 3:
									    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 노트에 당첨되셨습니다.';
									    		        break;
									    		    case 4:
									    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 커플컵에 당첨되셨습니다.';
									    		        break;
									    		    case 5:
									    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 마이보틀에 당첨되셨습니다.';
									    		        break;
									    		    case 6:
									    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 에코백에 당첨되셨습니다.';
									    		        break;
									    		    case 7:
									    		        alt = '사전예약에 참여해주셔서 감사합니다. 구글 기프트카드 5만원권에 당첨되셨습니다.';
									    		        break;
									    		    case 8:
									    		        alt = '사전예약에 참여해주셔서 감사합니다. 구글 기프트카드 3만원권에 당첨되셨습니다.';
									    		        break;
									    		    case 9:
									    		        alt = '사전예약에 참여해주셔서 감사합니다. 구글 기프트카드 1만원권에 당첨되셨습니다.';
									    		        break;
									    		    case 10:
									    		        alt = '사전예약에 참여해주셔서 감사합니다. 베스킨라빈스 상품권 1만원권에 당첨되셨습니다.';
									    		        break;
									    		    case 11:
									    		        alt = '사전예약에 참여해주셔서 감사합니다. 던킨 상품권 1만원권에 당첨되셨습니다.';
									    		        break;
									    		    case 12:
									    		        alt = '사전예약에 참여해주셔서 감사합니다. 스타벅스 아메리카노 기프티콘에 당첨되셨습니다.';
									    		        break;
									    		    case 13:
									    		        alt = '사전예약에 참여해주셔서 감사합니다. 아이패드 미니에 당첨되셨습니다.';
									    		        break;
									    		}
										    	}
										    }

									    	form.find("input[name=name], input[name=phNum2], input[name=phNum3], textarea").val("");
									    	pr.eq(0).hide();
									    	dimm.eq(0).show();
									    	pr.eq(1).find('.cont > img:first').attr('src',src);
									    	pr.eq(1).find('.cont > img:first').attr('alt',alt);
									    	pr.eq(1).show();
									    	dimm.eq(0).hide();

									    	if(sns){
									    		if (!preReserveUI) return;
										    	preReserveUI.moveLineup(idx);
										    }

									    	al.eq(3).find('.btn_fb2').unbind();
										}else{
											alert('등록오류입니다.\n다시 시도하여 주십시요.');
											pr.eq(0).show();
											al.eq(3).find('.btn_fb2').unbind();
										}
									});
				    		    } else {
				    		      alert('페이스북 등록 중 오류가 발생하였습니다.');
				    		    }
						    });
					});
					
					$('.ly_prev').hide();
				}else{
					return $.post("<?php echo site_url('pRAction')?>", $('#formPR').serialize()).done(function(data){
				    	if(data){
					    	var res = data.split("||")
					    	, idx = res[1].split(":")[1]
					    	, sns = res[2].split(":")[1]
					    	, src = '<?php echo $this->config->item('asset_url');?>/PC/img/gift_p.jpg',
					    	alt = '사전예약에 참여해주셔서 아쉽게도 당첨되지 않으셨네요. 꽝. 사전예약 이후에도 많은 관심 부탁드립니다.';
					    	
					    	if(res[0]){
					    		res = res[0].split(":");
					    		if(res[1]){
					    			src = '<?php echo $this->config->item('asset_url');?>/PC/img/gift_p'+res[1]+'.jpg'

					    			switch(res[1]) {
					    		    case 1:
					    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 티셔츠에 당첨되셨습니다.';
					    		        break;
					    		    case 2:
					    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 볼펜에 당첨되셨습니다.';
					    		        break;
					    		    case 3:
					    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 노트에 당첨되셨습니다.';
					    		        break;
					    		    case 4:
					    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 커플컵에 당첨되셨습니다.';
					    		        break;
					    		    case 5:
					    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 마이보틀에 당첨되셨습니다.';
					    		        break;
					    		    case 6:
					    		    	alt = '사전예약에 참여해주셔서 감사합니다. 캔디크러쉬소다 에코백에 당첨되셨습니다.';
					    		        break;
					    		    case 7:
					    		        alt = '사전예약에 참여해주셔서 감사합니다. 구글 기프트카드 5만원권에 당첨되셨습니다.';
					    		        break;
					    		    case 8:
					    		        alt = '사전예약에 참여해주셔서 감사합니다. 구글 기프트카드 3만원권에 당첨되셨습니다.';
					    		        break;
					    		    case 9:
					    		        alt = '사전예약에 참여해주셔서 감사합니다. 구글 기프트카드 1만원권에 당첨되셨습니다.';
					    		        break;
					    		    case 10:
					    		        alt = '사전예약에 참여해주셔서 감사합니다. 베스킨라빈스 상품권 1만원권에 당첨되셨습니다.';
					    		        break;
					    		    case 11:
					    		        alt = '사전예약에 참여해주셔서 감사합니다. 던킨 상품권 1만원권에 당첨되셨습니다.';
					    		        break;
					    		    case 12:
					    		        alt = '사전예약에 참여해주셔서 감사합니다. 스타벅스 아메리카노 기프티콘에 당첨되셨습니다.';
					    		        break;
					    		    case 13:
					    		        alt = '사전예약에 참여해주셔서 감사합니다. 아이패드 미니에 당첨되셨습니다.';
					    		        break;
					    		}
						    	}
						    }

					    	form.find("input[name=name], input[name=phNum2], input[name=phNum3], textarea").val("");
					    	pr.eq(0).hide();
					    	dimm.eq(0).show();
					    	pr.eq(1).find('.cont > img:first').attr('src',src);
					    	pr.eq(1).find('.cont > img:first').attr('alt',alt);
					    	pr.eq(1).show();
					    	dimm.eq(0).hide();

					    	if(sns){
					    		if (!preReserveUI) return;
						    	preReserveUI.moveLineup(idx);
						    }
					    	

					    	al.eq(3).find('.btn_fb2').unbind();
						}else{
							alert('등록오류입니다.\n다시 시도하여 주십시요.');
							al.eq(3).find('.btn_fb2').unbind();
						}
					});
				}
			}
			
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("pRAction/checkNamePhone");?>',
				data: {
					"name": form.find('input[name=name]').val(),
					"phNum1": form.find('input[name=phNum1]').val(),
					"phNum2": form.find('input[name=phNum2]').val(),
					"phNum3": form.find('input[name=phNum3]').val()
				},
				success: function(data){
		        }
			}).done(function(data){
				if(data == 2){
					if(!form.find('input[name=snsKind]').val()){
						dimm.eq(1).show();
						al.eq(0).find('p').attr('class','addto').html('줄서기에 같이 참여하면 당첨확률을 높일 수 있어요! 줄서기에도 참여하시겠습니까?');
						al.eq(0).find('.btn_group').html('<button type="button" class="btn_yes">확인</button><button type="button" class="btn_no">아니요</button>');
						al.eq(0).show();
						
						al.eq(0).find('button').on('click',function(e){
							e.preventDefault();
							var ct = e.currentTarget;
							if($(ct).attr('class') == 'btn_yes'){
								dimm.eq(1).hide();
								al.eq(0).hide();
								form.find('textarea[name="content"]').focus();
								return false;
							}else if($(ct).attr('class') == 'btn_no'){
								dimm.eq(1).hide();
								al.eq(0).hide();
								firstAjax().success(secondAjax);
							}
						});
					}else{
						firstAjax().success(secondAjax);				
					}
					
				}else{
					dimm.eq(0).show();
					
					if(data == 1){
						alert('핸드폰번호는 최소 10자리 이상이어야 합니다.');
						pr.eq(0).show();
					}else{
						al.eq(2).show();
						pr.eq(0).hide();
					}
					
					form.find('input[name=name]').val('');
					form.find('input[name=phNum2]').val('');
					form.find('input[name=phNum3]').val('');
					return false;
				}
			});

			return false;
	    });

        al.eq(0).find('button').click(function(e){			
			e.preventDefault();
			alert(1);
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
			dimm.eq(0).hide();			
		});

		//내위치보기 버튼
		$('.indicator').find('.my').click(function(e){
			e.preventDefault();
			var container = $('body'), alertPop = container.find('._alertMyPosition');
			
			if (container.find('._pagination').data('isMove')) {
				dimm.eq(0).show();
				
				var prm = alertPop.show().promise();
				$.when(prm).done(function() {
					alertPop.find('button').addClass('btn_confirm_s');
					alertPop.find('.btn_confirm_s').one('click', function() {
						alertPop.hide();
						dimm.eq(0).hide();
						alertPop.find('button').removeClass('btn_confirm_s');
					});
				});	
				return;
			}
			
			dimm.eq(0).show();
			al.eq(1).show();
		});

		al.eq(1).find('button').click(function(e){
			e.preventDefault();
			var t = $(e.target);
			if(t.hasClass('btn_fb')){
				if($('.snsKind').val() != 'fb'){
					var pop = window.open($('.snsKind').attr('data-fhref'), $('.snsKind').attr('data-href'), 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,top=0,left=0');
					if (pop.focus) setTimeout(function() {
						pop.focus();
					}, 200);
				}
			}else if(t.hasClass('btn_tt')){
				if($('.snsKind').val() != 'tt'){
					var pop = window.open($('.snsKind').attr('data-thref'), $('.snsKind').attr('data-href'), 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,top=0,left=0');
					if (pop.focus) setTimeout(function() {
						pop.focus();
					}, 200);
				}
			}
		});
		dimm.click(function(){
			dimm.hide();
			$('.ly_prev, .ly_alert').filter(function(){
				if($(this).css('display') != 'none'){
					$(this).hide();
				}
			});
		});

		//예약 form textarea 클릭시 sns 연결
		pr.eq(0).find('.inp_cheer > textarea').click(function(e){
			var t = $(e.target),
			snsKind = $('.snsKind');
			
			if(snsKind.val() != 'fb' &&  snsKind.val() != 'tt'){
				snsKind.val('fb');
				var pop = window.open(snsKind.attr('data-fhref'), snsKind.attr('data-href'), 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,top=0,left=0');
				if (pop.focus) setTimeout(function() {
					pop.focus();
				}, 200);
				$('.tab_sns').find('.fb').parent().addClass('on');
			}
		});
	});
})(jQuery, window);
</script>
