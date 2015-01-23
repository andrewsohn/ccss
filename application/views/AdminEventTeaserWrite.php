<?php
$ptitle = '이벤트 티져 관리 ';

if($view_mode == 'u'){
	$ptitle .= '수정';
	
	$et_opndate = substr($view->et_opendate,0,10);
	$et_opnhr = substr($view->et_opendate,11,2);
	$et_opnmin = substr($view->et_opendate,14,2);
	
	$et_clsdate = substr($view->et_closedate,0,10);
	$et_clshr = substr($view->et_closedate,11,2);
	$et_clsmin = substr($view->et_closedate,14,2);
}else{
	$ptitle .= '등록';
}

require_once './application/libraries/datepicker.php';
?>
<!-- container -->
<div id="container">
	<h2><?php echo $ptitle?></h2>
	<!-- 수정 -->
	<div class="tb_left">
	<?php echo validation_errors(); ?>
	<?php echo form_open('admin/eventTeaserAction', 'onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data"'); ?>
	<input type="hidden" name="w" value="<?php echo $view_mode ?>">
	<input type="hidden" name="et_id" value="<?php if(isset($et_id)) echo $et_id ?>">
	<input type="hidden" name="sfl" value="<?php if(isset($sfl)) echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php if(isset($stx)) echo $stx ?>">
	<input type="hidden" name="sst" value="<?php if(isset($sst)) echo $sst ?>">
	<input type="hidden" name="sod" value="<?php if(isset($sod)) echo $sod ?>">
	<input type="hidden" name="page" value="<?php if(isset($page)) echo $page ?>">
		<table>
		<caption><span>이벤트 티저 수정</span></caption>
		<colgroup><col style="width:150px"><col></colgroup>
		<tbody>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="et_subject">제목</label></div>
			</th>
			<td>
				<div class="td"><input type="text" name="et_subject" value="<?php if($view_mode) echo $view->et_subject ?>" id="et_subject" required class="inp_txt" size="80" maxlength="120"></div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="et_link">URL</label></div>
			</th>
			<td>
				<div class="td">
				<input type="text" name="et_link" value="<?php if($view_mode) echo $view->et_link ?>" id="et_link" required class="inp_txt" size="80" maxlength="120">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="et_opendate">시작일</label></div>
			</th>
			<td>
				<div class="td">
					<input type="text" name="et_opendate" value="<?php if($view_mode) echo $et_opndate ?>" id="et_opendate" readonly required class="opendate inp_txt v2" size="11" maxlength="10"/>
	            	<select name="et_openhr" id="et_openhr" title="시작 시각">
	            		<?php echo $this->common->printHrs($et_opnhr);?>
	            	</select>
	            	
	            	<select name="et_openmin" id="et_openmin" title="시작 분">
	            		<?php echo $this->common->printMin($et_opnmin);?>
	            	</select>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="et_closedate">마감일</label></div>
			</th>
			<td>
				<div class="td">
					<input type="text" name="et_closedate" value="<?php if($view_mode) echo $et_clsdate ?>" id="et_closedate" readonly required class="opendate inp_txt v2" size="11" maxlength="10"/>
	            	<select name="et_clshr" id="et_clshr" title="마감 시각">
	            		<?php echo $this->common->printHrs($et_clshr);?>
	            	</select>
	            	<select name="et_clsmin" id="et_clsmin" title="마감 분">
	            		<?php echo $this->common->printMin($et_clsmin);?>
	            	</select>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="et_mode">상태</label></div>
			</th>
			<td>
				<div class="td">
					<select name="et_mode" id="et_mode">
	            		<option value="">없음</option>
	            		<option value="0">비활성</option>
	            		<option value="1">활성</option>
	            		<option value="2">대기</option>
	            		<option value="3">취소</option>
	            	</select>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="et_content">메모</label></div>
			</th>
			<td>
				<div class="td">
					<?php 
					$et_content = '';
					if($view_mode) $et_content = $view->et_content;
					echo $this->smarteditor->editor_html("et_content", $this->common->get_text($et_content, 0)); ?>
				</div>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
		
	<!-- //수정 -->
	<div class="btn_group">
		<button type="submit" class="btn_o"><strong>확인</strong></button>
		<?php if($view_mode){?>
		<a href="<?php echo site_url("admin/EventTeaserAction").'/'.$et_id.$qstr.'&amp;w=d'?>" class="btn_d" onclick="del(this.href); return false;"><strong>삭제</strong></a>
		<?php }?>
		<a href="<?php echo site_url("admin/EventTeaser").$qstr?>" class="btn_g">취소</a>
	</div>
	<!-- //수정 -->
</div>
<!-- //container -->

<script>
$(function(){
	$(".opendate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true });
});

//삭제 검사 확인
function del(href)
{
    if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        var iev = -1;
        if (navigator.appName == 'Microsoft Internet Explorer') {
            var ua = navigator.userAgent;
            var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null)
                iev = parseFloat(RegExp.$1);
        }

        // IE6 이하에서 한글깨짐 방지
        if (iev != -1 && iev < 7) {
            document.location.href = encodeURI(href);
        } else {
            document.location.href = href;
        }
    }
}

function fboardform_submit(f)
{
	<?php echo $this->smarteditor->get_editor_js("et_content"); ?>
    return true;
}
</script>