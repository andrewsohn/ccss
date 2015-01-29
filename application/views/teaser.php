<?php
$upload_max_filesize = '2MB';
session_start();
$this->common->print_r2($this->session->userdata);


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

$id = '348697705319104';
$secret = '72acca56f341803ddada56ecefb4ad11';

FacebookSession::setDefaultApplication($id, $secret);

$helper = new FacebookRedirectLoginHelper(site_url('teaser'));
$session = $helper->getSessionFromRedirect();

if(isset($session)){
	// 		$_SESSION['token'] = $_GET['code'];
	$_SESSION['token'] = $session->getToken();
	$fba = '<a href="#" data-type="1" class="applyBtn">페북 나 이 곰 봤어요!</a>';
} else {
	$scope = array('publish_actions');
	$fbahref = $helper->getLoginUrl($scope);
	$fba = '<a href="'.$fbahref.'">페북 나 이 곰 봤어요!</a>';
}

$twa = '<a href="'.site_url('twitter').'">트위터 나 이 곰 봤어요!</a>';
if(isset($_SESSION['oauth_token']))
	$twa = '<a href="#" data-type="2" class="applyBtn">트위터 나 이 곰 봤어요!</a>';

$this->common->print_r2($_SESSION);
?>
<?php echo $fba?>
<?php echo $twa?>


<div class="popLayer" style="display: none;">
	<?php echo validation_errors(); ?>
	<?php echo form_open('applicantAction', 'onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="text" name="w" value="">
	<input type="text" name="et_id" id="et_id" value="<?php echo $view->idx?>">
	<input type="text" name="snsId" id="snsId" value="">
	<input type="text" name="name" id="name" value="">
	<table>
		<tr>
			<td><textarea name="bf_content" rows="10" cols="100" title="" class="" ></textarea></td>
		</tr>
		<tr>
			<td><input type="file" name="bf_file" title="파일첨부  : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능"></td>
		</tr>
		<tr>
			<td><input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_submit"></td>
		</tr>
	</table>
		<a href="#" class="close">X</a>
	</form>
</div>
<?php
$rows = 2;
echo "티저영상 페이지<br>";
echo '<h2>최신 이벤트 출력</h2>';
//echo $this->common->print_r2($view).'<br>';
echo '<h2>라이브된 이벤트 전체 출력</h2>';
/* for($i=0; $i<count($blist); $i++){
	echo $this->common->print_r2($blist[$i]).'<br>';
} */
echo '<h2>'.$view->title.' 이벤트 참여자</h2>';
//echo $this->common->print_r2($clist).'<br>';
?>
<ul class="clist">
<?php for($i=0; $i<count($clist); $i++){?>
<li>
	<dl>
		<dt>유저ID:<?php echo $clist[$i]->userIdx ?></dt>
		<dd>내용:<?php echo $clist[$i]->content ?></dd>
		<dd>날짜:<?php echo $clist[$i]->registDt ?></dd>
		<dd>SNS:<?php echo $clist[$i]->code_name ?></dd>
		<dd><a href="#" onclick="openShare(this);" data-idx="<?php echo $clist[$i]->idx ?>" class="pShare">공유</a></dd>
	</dl>
</li>
<?php }
?>
</ul>
<?php
if(count($clist)){ 
?>
<a href="#" class="btnMore" data-last="<?php echo $clist[count($clist)-1]->idx?>">더보기</a>
<img id="loading-indicator" src="<?php echo $this->config->item('asset_url');?>/admin/img/loading1.gif" style="display: none;"/>
<?php }?>
<script type="text/javascript">
openShare = function(a){
	/* 
	SNS 공유하기 필요값
	제목:
	내용:
	이미지:
	url:<?php echo site_url('teaser').'?cidx='?>
	 */
	 var href = "<?php echo site_url('ShareSNS').'?cidx='?>"+$(a).attr('data-idx')
	 ,new_win = window.open(href, 'win_share', 'left=100,top=100,width=600,height=580,scrollbars=0');
	new_win.focus();
	return false;
}

$(function(){
	var pl = $('.popLayer');
	pl.find('.close').click(function(e){
		e.preventDefault();
		$('#applyForm').each(function() {  
			this.reset();  
		});
		pl.hide();
	});
	$('.btnMore').click(function(e){
		e.preventDefault();
		var $this= $(this),
		 trg = $('.clist'),
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
					var result = eval(data);
					for (var i in result){
						var text = "<dt>유저ID:"+result[i]['userIdx']+"</dt>\n";
						text += "<dd>내용:"+result[i]['content']+"</dd>\n";
						text += "<dd>날짜:"+result[i]['registDt']+"</dd>\n";
						text += "<dd>SNS:"+result[i]['code_name']+"</dd>\n";
						text += '<dd><a href="#" onclick="openShare(this);" data-idx="'+result[i]['idx']+'" class="pShare">공유</a></dd>\n';
					    trg.append($('<li></li>').html($('<dl></dl>').html(text)));

					    if(i == (result.length-1))
					    	$this.attr('data-last',result[i]['idx']);    
					}
					
				}
			},
			beforeSend:function(){ 
		        //(이미지 보여주기 처리) 
		        $('.wrap-loading').show(); 
		    },
		    complete:function(){ 
		        //(이미지 감추기 처리) 
		        $('.wrap-loading').hide(); 
		    },
		    error:function(e){ 
		        //조회 실패일 때 처리 
		    },
		    timeout:100000
		});
	});
	$('.applyBtn').click(function(e){
		e.preventDefault();
		var trg = $(this),
		 sns = $(this).attr('data-type');
		if(sns == '1'){
			pl.find('#snsId').val(1);
			pl.find('#name').val('Facebook');
		}else if(sns == '2'){
			pl.find('#snsId').val(2);
			pl.find('#name').val('Twitter');
		}
		pl.find('#et_id').val('<?php echo $view->idx?>');
		pl.show();
	});

	$(document).ajaxSend(function(event, request, settings) {
	  $('#loading-indicator').show();
	});

	$(document).ajaxComplete(function(event, request, settings) {
	  $('#loading-indicator').hide();
	});
});
</script>