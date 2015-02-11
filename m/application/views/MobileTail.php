<?php if($this->router->fetch_class() == 'teaser'){?>
<!-- footer -->
<div id="footer">
	<div class="blind">
		<h1>주의 하세요!</h1>
		<ul>
		<li>등록한 사진과 내용은 본인의 페이스북 담벼락 및 현재 페이지에 보여집니다.</li>
		<li>이벤트 당첨자는 이벤트 종료 후 공식 블로그를 통해 공지됩니다. (추후 별도 개재)</li>
		<li>타인의 개인정보를 입력 또는 허위 정보를 입력하여 이벤트에 참여하는 경우 모든 당첨이 취소될 수 있습니다.</li>
		<li>작성한 글이 허위, 비방, 욕설 글로 판단 될 경우 예고없이 삭제될 수 있습니다.</li>
		<li>현물 경품은 이벤트 종료 후 2주일 내에 발송(당첨자 개별 안내) 예정이며, 쿠폰 경품은 이벤트 종료 당일 문자로 발송됩니다.</li>
		<li>잘못된 정보 입력으로 피해가 발생했을 경우 회사는 어떠한 책임도 부담하지 않습니다.</li>
		<li>이벤트 경품은 내부 사정에 따라 변경될 수 있습니다.</li>
		<li>아이패드 경품의 경우 제세공과금 22%가 부과됩니다.(당첨자 본인 부담)</li>
		</ul>
		<p>2015 &copy; bearfamily.net</p>
	</div>
</div>
<!-- //footer -->
</div>
<?php }else if($this->router->fetch_class() == 'preReserve'){?>
<!-- footer -->
<div id="footer">
	<div class="inn">
		<div class="caution">
			<h2 class="hx_caution">주의하세요!</h2>
			<ul>
			<li>모든 경품의 제세공과금은 King에서 부담합니다.</li>
			<li>경품은 사정에 따라 변경될 수 있으며 경품 이미지는 실제와 다를 수 있습니다.</li>
			<li>당첨된 경품 외, 다른 제품으로 변경이 불가합니다.</li>
			<li>본 이벤트 경품 당첨자는 추첨을 통해 선정되며,<br>
			당첨 공지는 각 이벤트에 명시되어 있는 당첨자 발표일에 <a href="http://www.candycrushsoda.co.kr/blog" target="_blank">King 공식 블로그</a>에서 발표됩니다.<br>
			( 단, 이벤트 안내 사항에 동의하지 않거나, 타인의 휴대전화 정보 또는 허위 정보를 입력하여 이벤트에 참여하는 경우 모든 당첨이 취소될 수 있습니다.)
			</li>
			<li>전 예약은 하나의 핸드폰 번호로만 참여 하실 수 있습니다.</li>
			<li>기타 부정한 방법으로 인한 당첨 시 당첨이 취소됩니다.</li>
			</ul>
		</div>
		<p class="copy">2015 ⓒ king.com</p>
	</div>
</div>
<!-- //footer -->

<!-- layer : alert -->
<div class="dimmed" style="display:none"></div>
<!-- 결과보기 -->
<div class="ly_alert gift" style="display:none">
	<img src="<?php echo $this->config->item('asset_url');?>/M/img/gift_p.jpg" alt="">
	<div class="btn_group">
		<button type="button" class="btn_confirm_s">확인</button>
	</div>
</div>
<!-- //결과보기 -->
<!-- 이름 -->
<div class="ly_alert" style="display:none">
	<p><span class="name">이름을 입력해주세요</span></p>
	<div class="btn_group">
		<button type="button" class="btn_confirm_s">확인</button>
	</div>
</div>
<!-- 이미 참여 -->
<div class="ly_alert fail" style="display:none">
	<p><span class="aleady">이미 참여하셨습니다.</span></p>
	<div class="btn_group">
		<button type="button" class="btn_confirm_s">확인</button>
	</div>
</div>
<!-- //이미 참여 -->

<?php }?>