<?php
$colspan = 8;
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
		<li <?php echo $cl?>><a href="<?php echo site_url("admin/EventApplicant").'?et_id='.$etList[$i]->idx?>"><?php echo $etList[$i]->title.' 참여자'?></a></li>
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
			$img_path = $this->config->item('asset_url').'/PC/img/@thumb/thumb.jpg';
			$ahref = $blist[$i]->content;
			$sh_yn = ' / 공유';
			if($blist[$i]->refIdx == ''){
				$filename = $blist[$i]->idx.'_thumb.'.$this->common->getValueByCode('20',$blist[$i]->photoType);
				$filepath = "http://2j5xlt4h84.ecn.cdn.infralab.net/data/event/".str_replace("-","",substr($blist[$i]->registDt,0,10)).'/'.$filename;
				
				$imgarr = getimagesize($filepath);
				
				if(is_array($imgarr)){
					$img_path = $filepath;
				}
				
				$ahref = '<a href="'; 
				if($blist[$i]->type == 1){
					$ahref .= 'https://www.facebook.com/app_scoped_user_id/'.$blist[$i]->userId.'/';
				}else if($blist[$i]->type == 2){
					$ahref .= 'https://twitter.com/intent/user?user_id='.$blist[$i]->userId;
				}
				$ahref .= '" target="_blank" class="eps">'.$blist[$i]->content.'</a>';
				$sh_yn = ' / 업로드';
			}
		?>
		<tr>
			<td><?php echo $blist[$i]->idx?></td>
			<td><?php echo $blist[$i]->event_name?></td>
			<td class="al"><?php echo $blist[$i]->userId?></td>
			<td><?php echo $this->common->getValueByCode(3,$blist[$i]->type).$sh_yn;?></td>
			<td class="al">
                <?php
                echo $ahref;
                if (isset($blist[$i]->icon_new)) echo $blist[$i]->icon_new;
                ?></td>
			<td class="img"><img src="<?php echo $img_path?>" alt=""></td>
			<td><?php echo $blist[$i]->registDt ?></td>
			<td class="btn">
			<a href="#" data-href="<?php echo $blist[$i]->idx; ?>" class="btn_g"><?php if($blist[$i]->status == 1) echo '숨기기'; else echo '보이기';?></a>
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
				if(data){
                	trg.html(data);
                }
			}
		});
	});
});
</script>