<?php
echo "로그인 페이지<br>";
echo $this->load->helper('url');
?>
<?php echo validation_errors(); ?>

<?php echo form_open('login/action'); ?>

<input type="text" name="rurl" value='<?php echo $rurl?>'>

    <fieldset id="login_fs">
        <legend>회원로그인</legend>
        <label for="login_id" class="login_id">회원아이디<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20">
        <label for="login_pw" class="login_pw">비밀번호<strong class="sound_only"> 필수</strong></label>
        <input type="password" name="mb_password" id="login_pw" class="frm_input" size="20" maxLength="20">
        <input type="submit" value="로그인" class="btn_submit">
        <input type="checkbox" name="auto_login" id="login_auto_login">
        <label for="login_auto_login">자동로그인</label>
    </fieldset>

    <aside id="login_info">
        <h2>회원로그인 안내</h2>
        <p>
            회원아이디 및 비밀번호가 기억 안나실 때는 아이디/비밀번호 찾기를 이용하십시오.<br>
            아직 회원이 아니시라면 회원으로 가입 후 이용해 주십시오.
        </p>
        <div>
            <a href="http://ccss.hivelab.co.kr/test_site/bbs/password_lost.php" target="_blank" id="login_password_lost" class="btn02">아이디 비밀번호 찾기</a>
            <a href="./register.php" class="btn01">회원 가입</a>
        </div>
    </aside>

    <div class="btn_confirm">
        <a href="http://ccss.hivelab.co.kr/test_site/">메인으로 돌아가기</a>
    </div>