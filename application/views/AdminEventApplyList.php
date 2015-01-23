<?php
$colspan = 6;
$et_id = '';
if(isset($_REQUEST['et_id'])){
	$et_id = (int)$_REQUEST['et_id'];
}
?>
<!-- container -->
<div id="container">
	<h2>이벤트 참여자 관리</h2>
	<ul class="tab">
		<li <?php if(!$et_id) echo 'class="on"';?>><a href="<?php echo site_url("admin/EventApplicant")?>">전체</a></li>
	<?php for($i=0; $i<count($etList); $i++){
		$cl = '';
		if($etList[$i]->et_id == $et_id){
			$cl = 'class="on"';
		}
	?>
		<li <?php echo $cl?>><a href="<?php echo site_url("admin/EventApplicant").'?et_id='.$etList[$i]->et_id?>"><?php echo $etList[$i]->et_subject.' 참여자'?></a></li>
	<?php }?>
	</ul>
	
	<!-- table -->
	<div class="tb_wrap">
		<table>
		<caption><span>사전예약 참여자 목록</span></caption>
		<colgroup><col style="width:7%"><col style="width:15%"><col style="width:11%"><col><col style="width:14%"><col style="width:11%"></colgroup>
		<thead>
		<tr>
			<th scope="col">고유번호</th>
			<th scope="col">작성자 ID</th>
			<th scope="col">TYPE</th>
			<th scope="col">작성내용</th>
			<th scope="col">작성일</th>
			<th scope="col">기능</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		for($i=0; $i<count($blist); $i++){
		?>
		<tr>
			<td><?php echo $blist[$i]->ea_id?></td>
			<td class="al">ABcdefgHIJKLMNopqrstu</td>
			<td>facebook</td>
			<td class="al">
			<a href="<?php echo $blist[$i]->href ?>" class="eps"><?php echo $blist[$i]->ea_content ?></a>
                <?php
                if (isset($blist[$i]->icon_new)) echo $blist[$i]->icon_new;
                ?></td>
			<td><?php echo $blist[$i]->ea_datetime ?></td>
			<td>
			<a href="#" class="btn_g">숨기기</a>
			<!-- 
			<a href="<?php //echo site_url("admin").'EventApplicantAction/'.$blist[$i]->ea_id;?>?w=d" onclick="return delete_confirm();">삭제</a> -->
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