<?php $colspan = 5;?>
<!-- container -->
<div id="container">
	<h2>이벤트 티져 관리</h2>
	<!-- table -->
	<div class="tb_wrap">
		<table>
		<caption><span>이벤트 티저 목록</span></caption>
		<colgroup><col style="width:8%"><col style="width:30%"><col style="width:33%"><col style="width:17%"><col style="width:12%"></colgroup>
		<thead>
		<tr>
			<th scope="col">고유번호</th>
	        <th scope="col">제목</a></th>
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
		<tr>
			<td><?php echo $blist[$i]->et_id?></td>
			<td class="al">
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
			<td class="al">
				<a href="<?php echo $blist[$i]->et_link?>"><?php echo $blist[$i]->et_link?></a>
			</td>
			<td><?php echo $blist[$i]->et_opendate ?></td>
			<td>
				<a href="<?php echo $blist[$i]->href ?>">수정</a>
	        	<a href="<?php echo site_url("admin/EventTeaserAction").'/'.$blist[$i]->et_id.$qstr.'&amp;w=d';?>" onclick="return delete_confirm();">삭제</a>
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
	<!-- //table -->
	<?php echo $this->pagination->create_links();?>
	<div class="btn_group">
		<a href="<?php echo site_url("admin/EventTeaser").'/new'.$qstr;?>" class="btn_o"><strong>신규이벤트 추가</strong></a>
	</div>
</div>
<!-- //container -->