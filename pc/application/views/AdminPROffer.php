<?php
$colspan = 7;
?>
<!-- container -->
<div id="container">
<!-- 사전예약 경품 관리 -->
	<h2>사전예약 경품 관리</h2>
	<!-- table -->
	<?php echo validation_errors(); ?>
	<?php echo form_open('admin/PROfferAction', 'method="post" enctype="multipart/form-data"'); ?>
	<input type="hidden" name="w" value="u">
	<div class="tb_wrap">
		<table>
		<caption><span>이벤트 경품 관리</span></caption>
		<colgroup><col style="width:7%"><col style="width:20%"><col><col style="width:17%"><col style="width:14%"><col style="width:10%"><col style="width:10%"></colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">경품</th>
			<th scope="col">당첨율</th>
			<th scope="col">일일 당첨 한도</th>
			<th scope="col">경품 수량</th>
			<th scope="col">금일 당첨</th>
			<th scope="col">누적 당첨</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		for($i=0; $i<count($blist); $i++){
		?>
		<tr>
			<td><?php echo $blist[$i]->idx?></td>
			<td class="al"><?php echo $blist[$i]->name?></td>
			<td class="ar"><input type="text" name="winningRate[]" class="inp_txt inp_ar _focusInput _num _limiter" value="<?php echo $blist[$i]->winningRate?>"> 명 중에 1명 당첨</td>
			<td class="ar"><input type="text" name="limitDailyWinGoods[]" class="inp_txt inp_ar _focusInput _num _limiter" value="<?php echo $blist[$i]->limitDailyWinGoods?>"> 개</td>
			<td><input type="text" name="amount[]" class="inp_txt inp_ar _focusInput _num _limiter" value="<?php echo $blist[$i]->amount?>"></td>
			<td><?php echo $blist[$i]->today_amount?></td>
			<td><?php echo $blist[$i]->total_amount?></td>
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
	<div class="btn_group">
		<button type="submit" class="btn_o"><strong>수정</strong></button>
		<button type="submit" class="btn_g">취소</button>
	</div>
	</form>
</div>
<!-- //container -->
<script>
$(function(){
	var at = $('.tb_wrap'), b = $('.btn_group');
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
				if(data){
                	trg.html(data);
                }
			}
		});
	});
	
	b.find('.btn_g').click(function(e){
		var trg = $(this);
		e.preventDefault();
		result = confirm("취소하시면 수정기록이 지워질 수도 있습니다.\n\n취소하시겠습니까?");
        if (result)
        	history.back(-1);
	});
});
</script>