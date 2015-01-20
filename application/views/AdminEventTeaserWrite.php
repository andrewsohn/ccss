<?php
$ptitle = '이벤트/티저 관리 ';
$frm_submit = '<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./board_list.php?">목록</a></div>'.PHP_EOL;

if($view->et_id){
	$ptitle .= '수정';
}else{
	$ptitle .= '등록';
}
$ptitle .= ' 페이지';
echo $ptitle.'<br>';

require_once './application/libraries/datepicker.php';
?>
<?php echo validation_errors(); ?>

<?php echo form_open('admin/eventTeaserAction', 'onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data"'); ?>
<input type="text" name="w" value="<?php echo $view_mode ?>">
<input type="hidden" name="sfl" value="<?php //echo $sfl ?>">
<input type="hidden" name="stx" value="<?php //echo $stx ?>">
<input type="hidden" name="sst" value="<?php //echo $sst ?>">
<input type="hidden" name="sod" value="<?php //echo $sod ?>">
<input type="hidden" name="page" value="<?php //echo $page ?>">

<h2 class="h2_frm"><?php echo $ptitle?></h2>

<section id="anc_bo_basic">
    <div class="">
        <table style="width: 100%;">
        <caption>이벤트 기본 설정</caption>
        <colgroup>
            <col width="10%">
            <col width="*">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="et_subject">이벤트 제목<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="text" name="et_subject" value="<?php echo $view->et_subject ?>" id="et_subject" required class="required" size="80" maxlength="120">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="et_visible">이벤트 상태<strong class="sound_only">필수</strong></label></th>
            <td>
            	<select name="et_visible" id="et_visible">
            		<option value="0" <?php //if( ) echo ?>>비활성</option>
            		<option value="1">활성</option>
            		<option value="2">대기</option>
            		<option value="3">취소</option>
            	</select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="et_opendate">시작일<strong class="sound_only">필수</strong></label></th>
            <td>
            	<input type="text" name="et_opendate" value="<?php echo $view->et_opendate ?>" id="et_opendate" readonly required class="opendate" size="11" maxlength="10"/>
            	<select name="et_openhr" id="et_openhr">
            		<option value="00" <?php //if( ) echo ?>>00시</option>
            		<option value="01">1시</option>
            		<option value="02">2시</option>
            		<option value="03">3시</option>
            	</select>
            	
            	<select name="et_openmin" id="et_openmin">
            		<option value="00" <?php //if( ) echo ?>>00분</option>
            		<option value="01">01분</option>
            		<option value="02">02분</option>
            		<option value="03">03분</option>
            	</select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="et_closedate">마감일<strong class="sound_only">필수</strong></label></th>
            <td>
            	<input type="text" name="et_closedate" value="<?php //echo $view->et_closedate ?>" id="et_closedate" readonly required class="opendate" size="11" maxlength="10"/>
            	<select name="et_closehr" id="et_closehr">
            		<option value="00" <?php //if( ) echo ?>>00시</option>
            		<option value="01">01시</option>
            		<option value="02">02시</option>
            		<option value="03">03시</option>
            	</select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="et_link">티저 유튜브 URL<strong class="sound_only">필수</strong></label></th>
            <td>
            	<input type="text" name="et_link" value="<?php echo $view->et_link ?>" id="et_link" required class="required" size="80" maxlength="120">
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>

<script>
$(function(){
	$(".opendate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true });
});

function fboardform_submit(f)
{
    if (parseInt(f.bo_count_modify.value) < 0) {
        alert("원글 수정 불가 댓글수는 0 이상 입력하셔야 합니다.");
        f.bo_count_modify.focus();
        return false;
    }

    if (parseInt(f.bo_count_delete.value) < 1) {
        alert("원글 삭제 불가 댓글수는 1 이상 입력하셔야 합니다.");
        f.bo_count_delete.focus();
        return false;
    }

    return true;
}
</script>