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

$id = '348697178652490';
$secret = '48b051b1e8230b0f1b44451055b7c921';

FacebookSession::setDefaultApplication($id, $secret);

$helper = new FacebookRedirectLoginHelper(site_url('preReserve'));
$session = $helper->getSessionFromRedirect();

if(isset($session)){
	// 		$_SESSION['token'] = $_GET['code'];
	$_SESSION['token'] = $session->getToken();
	$fba = '<a href="#" data-type="1" class="applyBtn">나 이 곰 봤어요! 페이스북에 올리기</a>';
} else {
	$scope = array('publish_actions');
	$fbahref = $helper->getLoginUrl($scope);
}

$twa = '<a href="'.site_url('twitter').'">나 이 곰 봤어요! 트위터에 올리기</a>';
if(isset($_SESSION['oauth_token']) && isset($_REQUEST['oauth_verifier'])){
	$_SESSION["oauth_verifier"] = $_GET["oauth_verifier"];
}

//$this->common->print_r2($_SESSION);
?>
<div class="ly_sns tt_sh" style="display:none">
<?php echo validation_errors(); ?>
<?php echo form_open('applicantAction', 'method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
<input type="hidden" name="w" value="s">
<input type="hidden" name="et_id" id="et_id" value="">
<input type="hidden" name="snsId" id="snsId" value="">
<input type="hidden" name="name" id="name" value="">
	<h1>트위터에 인증샷 올리기</h1>
	<h2>곰가족에게 하고싶은 말을 적어보세요</h2>
	<textarea name="bf_content" id="bf_content" cols="10" rows="5"></textarea>
	<span class="count">140/140</span>
	<div class="btn_group">
		<button type="submit" class="btn_regist">등록</button>
		<button type="button" class="btn_cancel">취소</button>
	</div>
</form>
</div>
<br>
<br>
<br>
<br>
<br>
<a href="#" class="btn_apply">사전예약 참여하기</a>
<?php
echo "사전예약 페이지<br>";
echo '<h2>사전 예약 참여자</h2>';
//echo $this->common->print_r2($clist).'<br>';
?>
<input type="hidden" id="target" value="">
<ul id="characters">
</ul>
<a href="#" data-mode="first" class="btn_apply"><-처음</a>
<a href="#" data-mode="pre" class="btn_apply"><-이전</a>
<a href="#" data-mode="my" data-type="fb" class="btn_apply">[페북]내위치보기</a>
<a href="#" data-mode="my" data-type="tt" class="btn_apply">[트위터]내위치보기</a>
<a href="#" data-mode="next" class="btn_apply">다음-></a>
<a href="#" data-mode="last" class="btn_apply">마지막-></a>
<script type="text/javascript">
$(function(){
	var l = $('#characters'),
	t = $('#target');
	$.ajax({
		type: "POST",
		url: '<?php echo site_url("preReserve/pRAction");?>',
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
				url: '<?php echo site_url("preReserve/pRAction");?>',
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
});
</script>