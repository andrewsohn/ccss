<?php
$colspan = 4;
echo "티져 동영상관리 목록 페이지<br>";
?>
<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="a.gr_id, a.bo_table">
<input type="hidden" name="sod" value="asc">
<input type="hidden" name="sfl" value="">
<input type="hidden" name="stx" value="">
<input type="hidden" name="page" value="1">
<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">
    <table border="1">
    <caption>게시판관리 목록</caption>
    <colgroup>
    	<col width="10%">
    	<col width="*">
    	<col width="20%">
    	<col width="20%">
    	<col width="10%">
    </colgroup>
    <thead>
    <tr>
        <th scope="col">고유번호</th>
        <th scope="col"><?php //echo subject_sort_link('bo_subject') ?>제목</a></th>
        <th scope="col">URL</th>
        <th scope="col">오픈일(mm/dd)</th>
        <th scope="col">기능</th>
    </tr>
    </thead>
    <tbody>
    <?php 
for($i=0; $i<count($blist); $i++){
	$bg = 'bg'.($i%2);
?>
		<tr class="<?php echo $bg; ?>">
			<td class="td_chk">
	        	<?php echo $blist[$i]->et_id?>
	        </td>
	        <td>
	        	<a href="<?php echo $blist[$i]->href ?>">
                    <?php echo $blist[$i]->et_subject ?>
                </a>

                <?php
                if (isset($blist[$i]->icon_new)) echo $blist[$i]->icon_new;
                if (isset($blist[$i]->icon_hot)) echo $blist[$i]->icon_hot;
                if (isset($blist[$i]->icon_file)) echo $blist[$i]->icon_file;
                if (isset($blist[$i]->icon_link)) echo $blist[$i]->icon_link;
                if (isset($blist[$i]->icon_secret)) echo $blist[$i]->icon_secret;
                ?>
	        </td>
	        <td class="td_chk">
	        	<?php echo $blist[$i]->et_link?>
	        </td>
	        <td class="td_chk">
	            <?php echo $blist[$i]->et_datetime ?>
	        </td>
	        <td class="td_mngsmall">
	        	<a href="<?php echo $blist[$i]->href ?>">수정</a>
	        	<a href="<?php echo site_url("admin").'EventTeaserAction/'.$blist[$i]->et_id;?>?w=d" onclick="return delete_confirm();">삭제</a>
	        </td>
	    </tr>
	<?php 
	}
	if ($i == 0)
		echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
	?>
    
        </tbody>
    </table>
</div>
</form>