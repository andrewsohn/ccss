	<?php echo validation_errors(); ?>
	<?php echo form_open('applicantAction2', 'method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
	<input type="hidden" name="w" value="">
		<h1>트위터에 인증샷 올리기</h1>
		<h2>곰가족에게 하고싶은 말을 적어보세요</h2>
		<textarea name="bf_content" id="bf_content" cols="10" rows="5"></textarea>
		<span class="count">140/140</span>
		<h2>사진을 첨부하세요</h2>
		<div class="upload">
			<input type="file" name="bf_file_tw">
			<!-- <input type="text" value="G:\PSD\ccss\150127_티저\\workspace" readonly> -->
			<!-- <button type="button" class="btn_file">찾아보기</button> --><!-- [D] type: file -->
		</div>
		<div class="btn_group">
			<button type="submit" class="btn_regist">등록</button>
			<button type="button" class="btn_cancel">취소</button>
		</div>
	</form>
