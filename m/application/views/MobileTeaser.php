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

$helper = new FacebookRedirectLoginHelper(site_url('teaserClose'));
$session = $helper->getSessionFromRedirect();

if(isset($session)){
	// 		$_SESSION['token'] = $_GET['code'];
	$_SESSION['token'] = $session->getToken();
	$fba = '<a href="#" data-type="1" class="applyBtn">나 이 곰 봤어요! 페이스북에 올리기</a>';
} else {
	$scope = array('publish_actions');
	$fbahref = $helper->getLoginUrl($scope);
	$fba = '<a href="#">나 이 곰 봤어요! 페이스북에 올리기</a>';
}

$twahref= site_url('twitter');
$twa = '<a href="#">나 이 곰 봤어요! 트위터에 올리기</a>';
if(isset($_SESSION['oauth_token']) && isset($_REQUEST['oauth_verifier'])){
	$_SESSION["oauth_verifier"] = $_GET["oauth_verifier"];
	$twa = '<a href="#" data-type="2" class="applyBtn">나 이 곰 봤어요! 트위터에 올리기</a>';
}
?>
<!-- container -->
	<div id="container">
		<h1 class="blind">곰나 귀여운 녀석들이 온다!</h1>
		<!-- movie -->
		<div class="mv">
			<div class="inr">
				<div class="blind">
					<h2>곰나 귀여운 녀석들이 온다!</h2>
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
		<!-- 소다곰 일정 버전 -->
		<div class="mv v2" style="display: none;">
			<div class="inr">
				<div class="blind">
					<h2>곰나 귀여운 녀석들이 온다!</h2>
					<p>캔디크러쉬소다와 함께 찾아온 곰가족의 좌우충돌 적응기!! 지금 영상으로 확인하세요~ 2015년 2월 12일 ~ 2015년 3월 1일</p>
				</div>
				<div class="mv_img"><button class="play">play</button></div>
				<ul class="go">
				<li><a href="#" class="fb">공식 페이스북</a></li>
				<li><a href="#" class="tt">공식 트위터</a></li>
				</ul>
				<!-- 소다곰 일정 보기 -->
				<a href="http://candycrushsoda.co.kr/blog/" class="btn_meet" target="_blank">여기가면 만날수 있다곰. 소다곰 일정 보기</a>
				<!-- //소다곰 일정 보기 -->
			</div>
		</div>
		<!-- 소다곰 일정 버전 -->
		
		<!-- 인증 -->
		<div class="proof">
			<div class="blind">
				<h2>곰가족을 보셨나요? 인증샷을 올려주세요!</h2>
				<p>길에서 곰가족을 만나셨나요? 이곳에 인증샷을 올려주세요. 추첨을 통해 다양한 선물을 드립니다.</p>
				<ul>
				<li>아이패드 1명</li>
				<li>브랜드 상품 1000명</li>
				<li>상품권 1000명</li>
				</ul> 
			</div>
			<ul id="snsBtn" class="sns">
			<input type="hidden" data-fhref="<?php echo $fbahref;?>" data-thref="<?php echo site_url('twitter/close2');?>" name="snsKind" class="snsKind" value="">
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
				
				$wh = 'width';
				if(is_array($imgarr)){
					$img_path = $filepath;
					if($imgarr[0]>$imgarr[1])
						$wh = 'height';
				}
				
				$ahref = '#';
				if($clist[$i]->type == 1){
					$ahref = 'https://www.facebook.com/app_scoped_user_id/'.$clist[$i]->userId.'/';
				}else if($clist[$i]->type == 2){
					$ahref = 'https://twitter.com/intent/user?user_id='.$clist[$i]->userId;
				}
			?>
			<li>
				<a href="<?php echo $ahref?>" target="_blank">
					<span class="tmb"><img src="<?php echo $img_path?>" style="<?php echo $wh?>:100%" alt=""></span><!-- [D] 가로, 세로 짧은 길이 기준으로 100% 사이즈 -->
					<div class="txt">
						<span class="tmb"><img src="<?php echo $clist[$i]->photoUrl ?>" style="width:100%" alt="<?php echo $clist[$i]->userName ?>프로필사진"></span><!-- [D] 가로, 세로 짧은 길이 기준으로 100% 사이즈 -->
						<em><?php echo $clist[$i]->userName ?></em><?php echo $this->common->getTime($clist[$i]->registDt);?>
						<p><?php echo $this->common->getShortenText($clist[$i]->content); ?></p>
					</div>
				</a>
				<button type="button" data-idx="<?php echo $clist[$i]->idx ?>" class="<?php if($clist[$i]->type == 1) echo 'btn_fb'; else echo 'btn_tt';?>"><span><?php if($clist[$i]->type == 1) echo '페이스북'; else echo '트위터';?> 공유</span></button>
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
	<?php echo form_open('mApplicantAction', 'onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="">
	<input type="hidden" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="hidden" name="snsId" id="snsId" value="1">
			<h3>페이스북에 인증샷 올리기</h3>
			<h4><label for="lb_txt_fb">소다곰에게 하고싶은 말을 적어보세요</label></h4>
			<textarea id="lb_txt_fb" name="bf_content" cols="10" rows="5"></textarea>
			<h4>사진을 첨부하세요</h4>
			<div class="upload">
				<input type="file" name="bf_file_fb" class="_file" style="display:none">
				<input type="text" class="_path" value="" readonly title="첨부된 파일 경로">
				<button type="button" class="btn_file _find" title="첨부파일 찾기">찾아보기</button>
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
	<?php echo form_open('mApplicantAction', 'onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="">
	<input type="hidden" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="hidden" name="snsId" id="snsId" value="2">
			<h3>트위터에 인증샷 올리기</h3>
			<h4><label for="bf_content">소다곰에게 하고싶은 말을 적어보세요</label></h4>
			<textarea name="bf_content" id="bf_content" cols="10" rows="5"></textarea>
			<span class="count">140/140</span>
			<h2>사진을 첨부하세요</h2>
			<div class="upload">
				<input type="file" name="bf_file_tw" class="_file" style="display:none">
				<input type="text" class="_path" value="" readonly title="첨부된 파일 경로">
				<button type="button" class="btn_file _find" title="첨부파일 찾기">찾아보기</button>
			</div>
			<div class="btn_group">
				<button type="submit" class="btn_regist">등록</button>
				<button type="button" class="btn_cancel">취소</button>
			</div>
			</form>
		</div>
		<!-- //트위터 인증샷 -->
		<!-- 페이스북 공유하기 -->
		<div class="ly_sns share_fb" style="display:none">
		<?php echo validation_errors(); ?>
	<?php echo form_open('mApplicantAction', 'onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="s">
	<input type="hidden" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="hidden" name="snsId" id="snsId" value="1">
	<input type="hidden" name="src" id="src" value="">
	<input type="hidden" name="refIdx" id="refIdx" value="">
			<h3>페이스북에 공유하기</h3>
			<h4><label for="lb_txt_fb2">소다곰에게 하고싶은 말을 적어보세요</label></h4>
			<div class="inp_share">
				<div class="thumb">
					<img src="<?php echo $this->config->item('asset_url');?>/M/img/@thumb/thumb.jpg" style="height:100%" alt="">
				</div>
				<textarea name="bf_content" id="lb_txt_fb2" cols="10" rows="5"></textarea>
			</div>
			<div class="btn_group">
				<button type="submit" class="btn_share">공유</button>
				<button type="button" class="btn_cancel">취소</button>
			</div>
		</form>
		</div>
		<!-- //페이스북 공유하기 -->
		<!-- 트위터 공유하기 -->
		<div class="ly_sns share_tt" style="display:none">
		<?php echo validation_errors(); ?>
	<?php echo form_open('mApplicantAction', 'onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="s">
	<input type="hidden" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="hidden" name="snsId" id="snsId" value="2">
	<input type="hidden" name="src" id="src" value="">
	<input type="hidden" name="refIdx" id="refIdx" value="">
			<h3>트위터에 공유하기</h3>
			<h4><label for="bf_content">소다곰에게 하고싶은 말을 적어보세요</label></h4>
			<div class="inp_share">
				<div class="thumb">
					<img src="<?php echo $this->config->item('asset_url');?>/M/img/@thumb/thumb.jpg" style="height:100%" alt="">
				</div>
				<textarea name="bf_content" id="bf_content" cols="10" rows="5"></textarea>
				<span class="count">140/140</span>
			</div>
			<div class="btn_group">
				<button type="submit" class="btn_share">공유</button>
				<button type="button" class="btn_cancel">취소</button>
			</div>
		</form>
		</div>
		<!-- //트위터 공유하기 -->
		<!-- 참여완료 -->
		<div class="ly_complete" style="display:none">
			<h3>참여완료</h3>
			<h4>참여해주셔서 감사합니다.</h4>
			<p>당첨 결과는 추첨을 통해<br>3월 5일 블로그에 게제될 예정입니다.<br>당첨 결과를 꼭 확인해주세요.</p>
			<button type="button" class="btn_comfirm">확인</button>
		</div>
		<!-- //참여완료 -->
		<!-- 업로드 완료 -->
		<div class="ly_complete v2" style="display:none">
			<h3>업로드 완료</h3>
			<h4>참여해주셔서 감사합니다.</h4>
			<p>해당 내용을 페이스북에 공유하셔야<br>이벤트 참여가 완료됩니다.</p>
			<button type="button" class="btn_fb">확인</button>
		</div>
		<!-- //업로드 완료 -->
		<!-- //[D] layer -->
	</div>
	<!-- //container -->

<script type="text/javascript">
(function($) {
    $.fn.extend( {
        limiter: function(limit, elem) {
        	$(this).on("keyup focus", function() {
                if($(this).next('span').hasClass('count')){
                	setCount(this, $(this).next('span'));   
                }
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

            if($(this).next('span').hasClass('count')){
            	setCount($(this)[0], $(this).next('span'));
        	}
        }
    });
})(jQuery);

openMainShare = function(a){
	 var href = "<?php echo site_url('ShareSNS').'?sns='?>"+$(a).attr('class')
	 ,new_win = window.open(href, 'win_main_share', 'left=100,top=100,width=600,height=580,scrollbars=0');
	new_win.focus();
	return false;
}

showCont = function(num, sns){
	$('.snsKind').val(sns);
	$('#container').find('.dimmed').show();
	$('.ly_sns').eq(num).show();
	$('.ly_sns').eq(num).find('textarea[name=bf_content]').focus();
}

fwrite_submit = function(f){
	if(f.bf_content != null){
		if (!f.bf_content.value) {
	        alert("내용을 입력하셔야 합니다.");
	        f.bf_content.focus();
	        return false;
	    }
	}

	if(f.bf_file_fb != null){
		if (!f.bf_file_fb.value) {
	        alert("사진 파일을 업로드 하셔야 합니다.");
	        f.bf_file_fb.focus();
	        return false;
	    }
	}
	
	if(f.bf_file_tw != null){
		if (!f.bf_file_tw.value) {
	        alert("사진 파일을 업로드 하셔야 합니다.");
	        document.getElementsByClassName("example")
	        f.bf_file_tw.focus();
	        return false;
	    }
	}
	
	return true;
}

$(function(){
	$.ajaxSetup({ cache: false });
	FB.init({
      appId: '<?php echo $this->config->item('fb_id');?>',
    });
	var cont = $('#container'), mv = cont.find('.mv'),
	pl = $('.ly_sns'),
	dimm = cont.find('.dimmed');
	
	pl.find('#bf_content').limiter(140);
	
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

	<?php if($this->session->flashdata('apply_complete') == 'teaser'){?>
	dimm.show();
		<?php if($this->session->flashdata('apply_img')){?>
		$('.ly_complete').eq(1).show();
		<?php }else{?>
		$('.ly_complete').eq(0).show();
		<?php }?>
	<?php }?>
	
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
			pl.eq(0).find('#et_id').val('<?php echo $view->idx?>');
			pl.eq(0).show();
		}else if(sns == '2'){
			pl.eq(1).find('#snsId').val(2);
			pl.eq(1).find('#et_id').val('<?php echo $view->idx?>');
			pl.eq(1).show();
		}
		dimm.show();
	});

	$('.btn_comfirm').click(function(e){
		e.preventDefault();
		dimm.hide();
		$('.ly_complete').hide();
	});
	/* $(document).ajaxSend(function(event, request, settings) {
	  $('#loading-indicator').show();
	});

	$(document).ajaxComplete(function(event, request, settings) {
	  $('#loading-indicator').hide();
	}); */

	    $(".ly_complete > .btn_fb").on('click',function(e){
		    e.preventDefault();
		    FB.ui({
		    	  method: 'feed',
		    	  //redirect_uri: '<?php echo site_url('teaser').'#snsBtn'?>',
		    	  link: '<?php echo site_url('teaser')?>',
		    	  picture: '<?php echo $this->session->flashdata('apply_img');?>',
		    	  description: '<?php echo $this->session->flashdata('apply_txt');?>'
		    	}, function(response){
		    		if (response && !response.error_code) {
		    			location.reload();
	    		    } else {
	    		      alert('페이스북 등록 중 오류가 발생하였습니다.');
	    		    }
			    });
		});     
});

</script>