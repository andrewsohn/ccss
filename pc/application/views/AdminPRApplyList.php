<?php
$colspan = 6;
?>
<!-- container -->
<div id="container">
	<h2>사전 예약 참여자 관리</h2>
	
	<!-- table -->
	<div class="tb_wrap">
		<table>
		<caption><span>사전예약 참여자 목록</span></caption>
		<colgroup><col style="width:7%"><col style="width:15%"><col style="width:11%"><col style="width:11%"><col><col style="width:14%"><col style="width:11%"></colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">작성자명</th>
			<th scope="col">핸드폰번호</th>
			<th scope="col">TYPE</th>
			<th scope="col">작성내용</th>
			<th scope="col">작성일</th>
			<th scope="col">기능</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		for($i=0; $i<count($blist); $i++){
			$ahref = '#';
			//$share_href = '';
			if($blist[$i]->type == 1){
				$ahref = 'https://www.facebook.com/app_scoped_user_id/'.$blist[$i]->userId.'/';
				//$share_href = site_url('fbShare');
			}else if($blist[$i]->type == 2){
				$ahref = 'https://twitter.com/intent/user?user_id='.$blist[$i]->userId;
				//$share_href = site_url('ttShare');
			}
		?>
		<tr>
			<td><?php echo $blist[$i]->idx?></td>
			<td class="al"><?php echo $blist[$i]->userName?></td>
			<td class="al"><?php echo $blist[$i]->mobileNum?></td>
			<td><?php if($blist[$i]->type) echo $this->common->getValueByCode(3,$blist[$i]->type);?></td>
			<td class="al"><a href="<?php echo $ahref?>" target="_blank" class="eps"><?php echo $blist[$i]->content ?></a><?php
                if (isset($blist[$i]->icon_new)) echo $blist[$i]->icon_new;
                ?></td>
			<td><?php echo $blist[$i]->registDt ?></td>
			<td><a href="#" data-href="<?php echo $blist[$i]->idx; ?>" class="btn_g"><?php if($blist[$i]->status == 1) echo '숨기기'; else echo '보이기';?></a></td>
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
	at.find('.btn_g').click(function(e){
		var trg = $(e.target);
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("admin/PRApplicantAction");?>',
			data: {
              "w": 'h',
              "idx": trg.attr('data-href')
			},
			success: function(data) {
				if(data){
                	trg.html(data);
                }
			}
		});
	});
});
</script>