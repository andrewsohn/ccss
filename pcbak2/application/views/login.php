<!-- container -->
<div id="container">
	<h1><img src="<?php echo $this->config->item('asset_url');?>/admin/img/logo2.png" alt="king"></h1>
	<!-- [D] input 포커스 시 label display:none -->
	<?php echo validation_errors(); ?>
	<?php echo form_open('login/action'); ?>
	<fieldset class="inp_login">
	<input type="hidden" name="rurl" value='<?php echo $rurl?>'>
		<legend class="blind">로그인</legend>	
		<span class="inp">
			<label for="login_id">아이디</label>
			<input type="text" name="mb_id" id="login_id" required class="_focusInput" size="20" maxLength="20">
		</span>
		<span class="inp">
			<label for="login_pw">비밀번호</label>
			<input type="password" name="mb_password" id="login_pw" required class="_focusInput" size="20" maxLength="20">
		</span>
		<span class="btn_b"><button type="submit">로그인</button></span>
	</fieldset>
</div>
<!-- //container -->
	