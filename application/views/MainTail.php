<?php if($this->router->fetch_class() == 'teaser'){?>
<!-- footer -->
	<div id="footer">
		<div class="img"><img src="<?php echo $this->config->item('asset_url');?>/PC/img/attention.jpg" alt=""></div>
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
	<div id="footer"><p>2015 &copy; KING.COM</p></div>
	<!-- //footer -->
</div>
<?php }?>