<?php
$ptitle = '이벤트 티져 관리 ';

if($view_mode == 'u'){
	$ptitle .= '수정';
}else{
	$ptitle .= '등록';
}
?>
<!-- container -->
<div id="container">
	<h2><?php echo $ptitle?></h2>
	<!-- 수정 -->
	<div class="tb_left">
	<?php echo validation_errors(); ?>
	<?php echo form_open('admin/eventTeaserAction', 'method="post" enctype="multipart/form-data"'); ?>
	<input type="hidden" name="w" value="<?php echo $view_mode ?>">
	<input type="hidden" name="idx" value="<?php if(isset($idx)) echo $idx ?>">
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
				<div class="th"><em>*</em><label for="title">제목</label></div>
			</th>
			<td>
				<div class="td"><input type="text" name="title" value="<?php if($view_mode) echo $view->title ?>" id="title" required class="inp_txt" size="80" maxlength="120"></div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="videoUrl">URL</label></div>
			</th>
			<td>
				<div class="td">
				<input type="text" name="videoUrl" value="<?php if($view_mode) echo $view->videoUrl ?>" id="videoUrl" required class="inp_txt" size="80" maxlength="120">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="status">상태</label></div>
			</th>
			<td>
				<div class="td">
					<select name="status" id="status">
	            		<option value="">없음</option>
	            		<?php echo $this->common->getOptByCode(2,$view->status);?>
	            	</select>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<div class="th"><em>*</em><label for="content">메모</label></div>
			</th>
			<td>
				<div class="td">
					<?php 
					$content = '';
					if($view_mode) $content = $view->content;
					?>
					<textarea name="content" id="content" ><?php echo $content?></textarea>
					<?php echo display_ckeditor($ckeditor); ?>
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
		<a href="<?php echo site_url("admin/EventTeaserAction").'/'.$idx.$qstr.'&amp;w=d'?>" class="btn_d" onclick="del(this.href); return false;"><strong>삭제</strong></a>
		<?php }?>
		<a href="<?php echo site_url("admin/EventTeaser").$qstr?>" class="btn_g">취소</a>
	</div>
	<!-- //수정 -->
</div>
<!-- //container -->

<script>
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
</script>