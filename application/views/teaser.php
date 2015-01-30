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

$helper = new FacebookRedirectLoginHelper(site_url('teaser'));
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

$twa = '<a href="'.site_url('twitter').'">나 이 곰 봤어요! 트위터에 올리기</a>';
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
		<h1 class="img"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/mv2.jpg" alt="곰나 귀여운 녀석들이 온다."></h1>
		<p class="blind">캔디크러쉬소다와 함께 찾아온 곰가족의 좌우충돌 적응기!! 지금 영상으로 확인하세요~ 2015년 2월 12일 ~ 2015년 3월 1일</p>
		<button data-href="<?php echo $view->videoUrl?>" class="play"><span>play</span></button>
		<!-- [D] layer : movie -->
		<div class="dimmed" style="display:none"></div>
		<div class="ly_movie" style="display:none">
			<div id="player"></div>
			<button type="button" class="btn_x">닫기</button>
		</div>
	</div>
	<!-- //movie -->
	
	<!-- 인증 -->
	<div class="proof">
		<h1 class="img"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/proof.jpg" alt="곰가족을 보셨나요? 인증샷을 올려주세요!"></h1>
		<div class="blind">
			<p>길에서 곰가족을 만나셨나요? 이곳에 인증샷을 올려주세요. 추첨을 통해 다양한 선물을 드립니다. 길에서 도저히 보이질 않는다구요? 친구의 글을 공유하기만 해도 이벤트 참여가 가능합니다.</p>
			<ul>
			<li>step1. 곰가족의 사진을 찍는다.</li>
			<li>step2. candycrushsoda.co.kr에 접속하여 사진을 올린다.</li>
			<li>step3. 당첨결과를 기다린다.</li>
			<li>경품. 아이패드1명 / 상품권 1000명</li>
			</ul> 
		</div>
		<ul id="snsBtn" class="sns">
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
			//$share_href = '';
			if($clist[$i]->type == 1){
				$ahref = 'https://www.facebook.com/app_scoped_user_id/'.$clist[$i]->userId.'/';
				//$share_href = site_url('fbShare');
			}else if($clist[$i]->type == 2){
				$ahref = 'https://twitter.com/intent/user?user_id='.$clist[$i]->userId;
				//$share_href = site_url('ttShare');
			}
		?>
		<li>
			<a href="<?php echo $ahref?>" target="_blank">
				<span class="tmb"><img src="<?php echo $img_path?>" style="height:100%" alt=""></span><!-- [D] 가로, 세로 짧은 길이 기준으로 100% 사이즈 -->
				<div class="txt">
					<span class="tmb"><img src="<?php echo $clist[$i]->photoUrl ?>" style="width:100%" alt="<?php echo $clist[$i]->userName ?>프로필사진"></span><!-- [D] 가로, 세로 짧은 길이 기준으로 100% 사이즈 -->
					<em><?php echo $clist[$i]->userName ?></em><?php echo $this->common->getTime($clist[$i]->registDt);?>
					<p><?php echo $this->common->getShortenText($clist[$i]->content); ?></p>
				</div>
			</a>
			<button data-href="<?php //echo $share_href?>" data-idx="<?php echo $clist[$i]->idx ?>" class="<?php if($clist[$i]->type == 1) echo 'btn_fb'; else echo 'btn_tt';?>"><span><?php if($clist[$i]->type == 1) echo '페이스북'; else echo '트위터';?> 공유</span></button>
		</li>
		<?php }?>
		</ul>
		<?php
		if(count($clist)){ 
		?>
		<button class="more" data-last="<?php echo $clist[count($clist)-1]->idx?>"><span>더보기</span></button>
		<img id="loading-indicator" src="<?php echo $this->config->item('asset_url');?>/admin/img/loading1.gif" style="display: none;"/>
		<?php }?>
		<!-- //[D] 인증 리스트 -->
	</div>
	<!-- //인증 -->
	
	<!-- [D] layer -->
	<div class="dimmed" style="display:none"></div>
	<!-- 페이스북 인증샷 -->
	<div class="ly_sns fb" style="display:none">
	<?php echo validation_errors(); ?>
	<?php echo form_open('applicantAction', 'method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="">
	<input type="hidden" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="hidden" name="snsId" id="snsId" value="">
	<input type="hidden" name="name" id="name" value="">
		<h1>페이스북에 인증샷 올리기</h1>
		<h2>곰가족에게 하고싶은 말을 적어보세요</h2>
		<textarea name="bf_content" cols="10" rows="5"></textarea>
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
	<?php echo form_open('applicantAction', 'method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="">
	<input type="hidden" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="hidden" name="snsId" id="snsId" value="">
	<input type="hidden" name="name" id="name" value="">
		<h1>트위터에 인증샷 올리기</h1>
		<h2>곰가족에게 하고싶은 말을 적어보세요</h2>
		<textarea name="bf_content" id="bf_content" cols="10" rows="5"></textarea>
		<span class="count">140/140</span>
		<h2>사진을 첨부하세요</h2>
		<div class="upload">
			<input type="file" name="bf_file_tw">
			<!-- <input type="text" value="G:\PSD\ccss\150127_티저\\workspace" readonly> -->
			<!-- <button type="button" class="btn_file">찾아보기</button> --><!-- [D] type: file -->
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
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
var player;

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
    	var $this = mv.find('.play');
    			var temp = $this.attr("data-href");
    			
    			var vid_id = '';
    			if(temp.indexOf("youtu.be") > -1){
        			//영상url에 공유 url을 삽입한 경우(youtu.be)
    				var res = temp.split("/");
        		}else{
            		var res = temp.split("v=");
            	}
    			vid_id = res[res.length-1];
    			player = new YT.Player('player', {
    			      height: mv.find(".ly_movie").height(),
    			      width: mv.find(".ly_movie").width(),
    			      videoId: vid_id,
    			      events: {
    			          'onStateChange': function (event) {
    			            if (event.data == 0 || event.data == 2) {
    			            	player.destroy();
    			            	mv.find(".ly_movie").hide();
    			            }
    			          }
    			      },
    			      playerVars: { 'autoplay': 1, 'controls': 0, 'showinfo':0 }
			      
    			    });
        		
    			dimm.show();
        		mv.find(".ly_movie").show();
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
			url: '<?php echo site_url("teaser/getMoreList");?>',
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