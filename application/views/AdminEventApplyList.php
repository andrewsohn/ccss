<?php
$colspan = 7;
?>
<!-- container -->
<div id="container">
	<h2>이벤트 참여자 관리</h2>
	<ul class="tab">
		<li <?php if(!$et_id) echo 'class="on"';?>><a href="<?php echo site_url("admin/EventApplicant")?>">전체</a></li>
	<?php for($i=0; $i<count($etList); $i++){
		$cl = '';
		if($etList[$i]->idx == $et_id){
			$cl = 'class="on"';
		}
	?>
		<li <?php echo $cl?>><a href="<?php echo site_url("admin/EventApplicant").'?et_id='.$etList[$i]->idx?>"><?php echo $etList[$i]->name.' 참여자'?></a></li>
	<?php }?>
	</ul>
	
	<!-- table -->
	<div class="tb_wrap">
		<table>
		<caption><span>사전예약 참여자 목록</span></caption>
		<colgroup><col style="width:7%"><col style="width:15%"><col style="width:15%"><col style="width:11%"><col><col><col style="width:14%"><col style="width:11%"></colgroup>
		<thead>
		<tr>
			<th scope="col">고유번호</th>
			<th scope="col">이벤트</th>
			<th scope="col">작성자 ID</th>
			<th scope="col">TYPE</th>
			<th scope="col">작성내용</th>
			<th scope="col">사진</th>
			<th scope="col">작성일</th>
			<th scope="col">기능</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		for($i=0; $i<count($blist); $i++){
		?>
		<tr>
			<td><?php echo $blist[$i]->idx?></td>
			<td><?php echo $blist[$i]->event_name?></td>
			<td class="al"><?php echo $blist[$i]->userIdx?></td>
			<td><?php echo $blist[$i]->type?></td>
			<td class="al">
			<a href="#" class="eps"><?php echo $blist[$i]->content ?></a>
                <?php
                if (isset($blist[$i]->icon_new)) echo $blist[$i]->icon_new;
                ?></td>
			<td class="img"><img src="http://imgnews.naver.net/image/003/2013/05/18/NISI20130518_0008193217_web_59_20130518092610.jpg" alt=""></td>
			<td><?php echo $blist[$i]->registDt ?></td>
			<td class="btn">
			<a href="#" data-href="<?php echo $blist[$i]->idx; ?>" class="btn_g">
			<?php
			if($blist[$i]->visible == 'N'){
				echo '보이기'; 
			}else{
				echo '숨기기';
			} 
			?>
			</a>
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
	<?php echo $this->pagination->create_links();?>
	<!-- //paging -->
</div>
<!-- //container -->
<script>
$(function(){
	var at = $('.tb_wrap');
	at.find('.btn > a').click(function(e){
		var trg = $(this);
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("admin/EventApplicantAction");?>',
			data: {
              "w": 'h',
              "idx": trg.attr('data-href')
			},
			success: function(data) {
				if(data == 'N'){
                	trg.html('숨기기');
                }else{
                	trg.html('보이기');
                }
			}
		});
	});
});
</script>