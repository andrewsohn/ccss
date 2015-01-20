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

<?php echo form_open('login/action'); ?>
<!-- <form name="fboardform" id="fboardform" action="./board_form_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data"> -->

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
            <th scope="row"><label for="et_opendate">오픈일<strong class="sound_only">필수</strong></label></th>
            <td>
            	<input type="text" name="et_opendate" value="<?php echo $view->et_opendate ?>" id="et_opendate" readonly required class="opendate" size="11" maxlength="10"/>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="et_link">티저 URL<strong class="sound_only">필수</strong></label></th>
            <td>
            	<input type="text" name="et_link" value="<?php echo $view->et_link ?>" id="et_link" required class="required" size="80" maxlength="120">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_subject">이벤트 내용<strong class="sound_only">필수</strong></label></th>
            <td>
                <?php echo $this->smarteditor->editor_html("bo_content_head", $this->common->get_text($view->et_content, 0)); ?>
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
    <?php echo $this->smarteditor->get_editor_js("bo_content_head"); ?>

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