<?php
$colspan = 8;
?>
<!-- container -->
<div id="container">
	<h2>이벤트 참여자 관리</h2>
	<!-- table -->
	<div class="tb_wrap">
		<table>
		<caption><span>이벤트 참여자 목록</span></caption>
		<colgroup><col style="width:6%"><col style="width:11%"><col style="width:14%"><col style="width:12%"><col style="width:30%"><col style="width:15%"><col style="width:11%"><col style="width:11%"></colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">SNS</th>
			<th scope="col">이름</th>
			<th scope="col">사진</th>
			<th scope="col">작성글</th>
			<th scope="col">작성일(mm/dd)</th>
			<th scope="col">상태</th>
			<th scope="col">기능</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		for($i=0; $i<count($blist); $i++){
			$bg = 'bg'.($i%2);
		?>
		<tr>
			<td><?php echo $blist[$i]->ea_id?></td>
			<td>facebook</td>
	        <td>
	        	<a href="<?php echo $blist[$i]->href ?>">
                    <?php echo $blist[$i]->ea_subject ?>
                </a>

                <?php
                if (isset($blist[$i]->icon_new)) echo $blist[$i]->icon_new;
                ?>
	        </td>
	        <td class="img"><img src="http://img.ezmember.co.kr/cache/board/2015/01/13/TMP_b5d7b6e5834b681b5141691c9b315ba6.jpg" alt=""></td>
	        <!-- <td> -->
	        	<?php //echo $blist[$i]->ea_link?>
	        <!-- </td> -->
	        <td class="al">먼저 물고기를 잡는 시범을 보인 뒤 따라 하도록 했다. 이제 갖 8개월 된 새끼 곰은 사냥에 실패하고 말았지만, 어미 곰이 잡은 연어를 가지고 집으로 향했다.</td>
	        <td>
	            <?php echo $blist[$i]->ea_datetime ?>
	        </td>
	        <td>비활성</td>
	        <td>
	        	<a href="<?php echo $blist[$i]->href ?>">수정</a>
	        	<a href="<?php echo site_url("admin").'EventApplicantAction/'.$blist[$i]->ea_id;?>?w=d" onclick="return delete_confirm();">삭제</a>
	        </td>
	    </tr>
		<?php 
		}
		if ($i == 0)
			echo '<tr><td colspan="'.$colspan.'">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>
	<!-- //table -->
	
	<!-- paging -->
	<div class="paging">
		<a href="#" class="prev"><span class="sp"></span><span>처음</span></a><a href="#" class="before"><span class="sp"></span>이전</a>
		<a href="#" class="on">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a><a href="#">7</a><a href="#">8</a><a href="#">9</a><a href="#">999</a>
		<a href="#" class="next">다음<span class="sp"></span></a><a href="#" class="end">끝<span class="sp"></span></a>
	</div>
	<!-- //paging -->
</div>
<!-- //container -->

<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="a.gr_id, a.bo_table">
<input type="hidden" name="sod" value="asc">
<input type="hidden" name="sfl" value="">
<input type="hidden" name="stx" value="">
<input type="hidden" name="page" value="1">
<input type="hidden" name="token" value="">

</form>