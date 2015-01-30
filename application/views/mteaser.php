<?php
$upload_max_filesize = '2MB';
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

$id = '348697178652490';
$secret = '48b051b1e8230b0f1b44451055b7c921';

FacebookSession::setDefaultApplication($id, $secret);

$helper = new FacebookRedirectLoginHelper(site_url('m/teaser'));
$session = $helper->getSessionFromRedirect();

if(isset($session)){
	// 		$_SESSION['token'] = $_GET['code'];
	$_SESSION['token'] = $session->getToken();
	$fba = '<a href="#" data-type="1" class="applyBtn">나 이 곰 봤어요! 페이스북에 올리기</a>';
} else {
	$scope = array('publish_actions');
	$fbahref = $helper->getLoginUrl($scope);
	$fba = '<a href="'.$fbahref.'">나 이 곰 봤어요! 페이스북에 올리기</a>';
}

$twa = '<a href="'.site_url('twitter?device=m').'">나 이 곰 봤어요! 트위터에 올리기</a>';
if(isset($_SESSION['oauth_token']) && isset($_REQUEST['oauth_verifier'])){
	$_SESSION["oauth_verifier"] = $_GET["oauth_verifier"];
	$twa = '<a href="#" data-type="2" class="applyBtn">나 이 곰 봤어요! 트위터에 올리기</a>';
}

//$this->common->print_r2($_SESSION);
?>
<!-- container -->
	<div id="container">
		<!-- movie -->
		<div class="mv">
			<div class="inr">
				<div class="blind">
					<h1>곰나 귀여운 녀석들이 온다!</h1>
					<p>캔디크러쉬소다와 함께 찾아온 곰가족의 좌우충돌 적응기!! 지금 영상으로 확인하세요~ 2015년 2월 12일 ~ 2015년 3월 1일</p>
				</div>
				<div class="mv_img"><button data-href="<?php echo $view->videoUrl?>" class="play">play</button></div>
				<ul class="go">
				<li><a href="#" onclick="openMainShare(this);" class="fb">공식 페이스북</a></li>
				<li><a href="#" onclick="openMainShare(this);" class="tt">공식 트위터</a></li>
				</ul>
			</div>
		</div>
		<!-- //movie -->
		
		<!-- 인증 -->
		<div class="proof">
			<div class="blind">
				<h1>곰가족을 보셨나요? 인증샷을 올려주세요!</h1>
				<p>길에서 곰가족을 만나셨나요? 이곳에 인증샷을 올려주세요. 추첨을 통해 다양한 선물을 드립니다.</p>
				<ul>
				<li>아이패드 1명</li>
				<li>브랜드 상품 1000명</li>
				<li>상품권 1000명</li>
				</ul> 
			</div>
			<ul class="sns">
			<li><?php echo $fba?></li>
			<li><?php echo $twa?></li>
			</ul>
			<!-- [D] 인증 리스트 -->
			<ul class="lst">
			<?php for($i=0; $i<count($clist); $i++){
				$img_path = $this->config->item('asset_url').'/PC/img/@thumb/thumb.jpg';
				$filename = $clist[$i]->idx.'_thumb.'.$this->common->getValueByCode('20',$clist[$i]->photoType);
				$filepath = "http://2j5xlt4h84.ecn.cdn.infralab.net/data/event/".str_replace("-","",substr($clist[$i]->registDt,0,10)).'/'.$filename;
					
				$imgarr = getimagesize($filepath);
					
				if(is_array($imgarr)){
					$img_path = $filepath;
				}
				
				$ahref = '#';
				if($clist[$i]->type == 1){
					//$ahref = 'https://www.facebook.com/profile.php?id='.$clist[$i]->userId;
					$ahref = 'https://www.facebook.com/app_scoped_user_id/'.$clist[$i]->userId.'/';
				}else if($clist[$i]->type == 2){
					$ahref = 'https://twitter.com/intent/user?user_id='.$clist[$i]->userId;
				}
			?>
			<li>
				<a <?php echo $ahref?>" target="_blank">
					<span class="tmb"><img src="<?php echo $img_path?>" style="width:100%" alt=""></span><!-- [D] 가로, 세로 짧은 길이 기준으로 100% 사이즈 -->
					<div class="txt">
						<span class="tmb"><img src="<?php echo $clist[$i]->photoUrl ?>" style="width:100%" alt="<?php echo $clist[$i]->userName ?>프로필사진"></span><!-- [D] 가로, 세로 짧은 길이 기준으로 100% 사이즈 -->
						<em><?php echo $clist[$i]->userName ?></em><?php echo $this->common->getTime($clist[$i]->registDt);?>
						<p><?php echo $this->common->getShortenText($clist[$i]->content); ?></p>
					</div>
				</a>
				<button onclick="openShare(this);" data-idx="<?php echo $clist[$i]->idx ?>" class="<?php if($clist[$i]->type == 1) echo 'btn_fb'; else echo 'btn_tt';?>"><span><?php if($clist[$i]->type == 1) echo '페이스북'; else echo '트위터';?> 공유</span></button>
			</li>
			<?php }?>
			</ul>
			<!-- //[D] 인증 리스트 -->
			<?php
			if(count($clist)){ 
			?>
			<button class="more" data-last="<?php echo $clist[count($clist)-1]->idx?>"><span>더보기</span></button>
			<?php }?>
		</div>
		<!-- //인증 -->

		<!-- [D] layer -->
		<div class="dimmed" style="display:none"></div>
		<!-- 페이스북 인증샷 -->
		<div class="ly_sns fb" style="display:none">
		<?php echo validation_errors(); ?>
	<?php echo form_open('mApplicantAction', 'method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="">
	<input type="hidden" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="hidden" name="snsId" id="snsId" value="">
	<input type="hidden" name="name" id="name" value="">
			<h1>페이스북에 인증샷 올리기</h1>
			<h2>곰가족에게 하고싶은 말을 적어보세요</h2>
			<textarea name="bf_content" cols="10" rows="5">앗!! 그 곰이 아닌가?</textarea>
			<h2>사진을 첨부하세요</h2>
			<div class="upload">
				<input type="file" name="bf_file_fb">
				<!-- <input type="text" value="" readonly>
				<button type="button" class="btn_file">찾아보기</button> --><!-- [D] type: file -->
			</div>
			<div class="btn_group">
				<button type="submit" class="btn_regist">등록</button>
				<button type="button" class="btn_cancel">취소</button>
			</div>
			</form>
		</div>
		<!-- //페이스북 인증샷 -->
		<!-- 트위터 인증샷 -->
		<div class="ly_sns tt" style="display:none">
		<?php echo validation_errors(); ?>
	<?php echo form_open('mApplicantAction', 'method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="">
	<input type="hidden" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="hidden" name="snsId" id="snsId" value="">
	<input type="hidden" name="name" id="name" value="">
			<h1>트위터에 인증샷 올리기</h1>
			<h2>곰가족에게 하고싶은 말을 적어보세요</h2>
			<textarea name="bf_content" id="bf_content" cols="10" rows="5">시리자의 알렉시스 치프라스는 이번 총선에서 13석을 차지한 그리스독립당의 파노스 캄메노스 당수를 만났다. 이어 득표율 4위인 포타미의 스타브로스 테오도라키스 당수와도 회동해 연정 구성을 협의하기로 했다.</textarea>
			<span class="count">140/140</span>
			<h2>사진을 첨부하세요</h2>
			<div class="upload">
				<input type="file" name="bf_file_tw">
				<!-- <input type="text" value="G:\PSD_ETC\ccss\150127_티저\\workspace" readonly>
				<button type="button" class="btn_file">찾아보기</button> --><!-- [D] type: file -->
			</div>
			<div class="btn_group">
				<button type="submit" class="btn_regist">등록</button>
				<button type="button" class="btn_cancel">취소</button>
			</div>
			</form>
		</div>
		<!-- //트위터 인증샷 -->
		<!-- //[D] layer -->
	</div>
	<!-- //container -->

<script type="text/javascript">
(function($) {
    $.fn.extend( {
        limiter: function(limit, elem) {
            $(this).on("keyup focus", function() {
                setCount(this, elem);
            });
            function setCount(src, elem) {
                var chars = src.value.length;
                if (chars > limit) {
                    src.value = src.value.substr(0, limit);
                    chars = limit;
                }
                snum = limit - chars
                elem.html( snum + '/140');
            }
            setCount($(this)[0], elem);
        }
    });
})(jQuery);

openMainShare = function(a){
	 var href = "<?php echo site_url('ShareSNS').'?sns='?>"+$(a).attr('class')
	 ,new_win = window.open(href, 'win_main_share', 'left=100,top=100,width=600,height=580,scrollbars=0');
	new_win.focus();
	return false;
}

openShare = function(a){
	 var href = "<?php echo site_url('ShareSNS').'?cidx='?>"+$(a).attr('data-idx')
	 ,new_win = window.open(href, 'win_share', 'left=100,top=100,width=600,height=580,scrollbars=0');
	new_win.focus();
	return false;
}

$(function(){
	var cont = $('#container'), mv = cont.find('.mv'),
	pl = $('.ly_sns'),
	elem = pl.eq(1).find('.count'),
	dimm = cont.find('.dimmed');
	
	pl.eq(1).find('#bf_content').limiter(140, elem);
	
	pl.find('.btn_cancel').click(function(e){
		e.preventDefault();
		$('#applyForm').each(function() {  
			this.reset();  
		});
		$(this).closest('.ly_sns').hide();
		dimm.hide();
	});

	//유튜브 영상
	mv.find('.play').click(function(e) {
    	e.preventDefault();
    	var url = $(this).attr('data-href');
    	if (!url) return;
		var pop = window.open(url, url, 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,top=0,left=0');
		if (pop.focus) setTimeout(function() {
			pop.focus();
		}, 200);
    });
	cont.find('.btn_x, .dimmed').click(function(e) {
		e.preventDefault();
		if(mv.find(".ly_movie").css('display') != 'none'){
			player.destroy();
			mv.find(".ly_movie").hide();
		}else{
			cont.find(".ly_sns").hide();
		}
		
		dimm.hide();
		
	});
    
	$('.more').click(function(e){
		e.preventDefault();
		var $this= $(this),
		 $trg = $('.lst'),
		 last = $this.attr('data-last');
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("m/getMoreList");?>',
			data: {
             "idx": '<?php echo $view->idx?>',
             "idx2": last
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
	$('.applyBtn').click(function(e){
		e.preventDefault();
		var trg = $(this),
		 sns = $(this).attr('data-type');
		if(sns == '1'){
			pl.eq(0).find('#snsId').val(1);
			pl.eq(0).find('#name').val('Facebook');
			pl.eq(0).find('#et_id').val('<?php echo $view->idx?>');
			pl.eq(0).show();
		}else if(sns == '2'){
			pl.eq(1).find('#snsId').val(2);
			pl.eq(1).find('#name').val('Twitter');
			pl.eq(1).find('#et_id').val('<?php echo $view->idx?>');
			pl.eq(1).show();
		}
		dimm.show();
	});

	/* $(document).ajaxSend(function(event, request, settings) {
	  $('#loading-indicator').show();
	});

	$(document).ajaxComplete(function(event, request, settings) {
	  $('#loading-indicator').hide();
	}); */
});
</script>