<?php if($this->router->fetch_class() == 'teaser'){?>
<!-- footer -->
	<div id="footer">
		<div class="img">
			<img src="<?php echo $this->config->item('asset_url');?>/PC/img/attention.jpg" alt="">
			<a href="http://candycrushsoda.co.kr/blog/" class="go_blog" target="_blank">캔디크러쉬소다 공식 블로그 바로가기</a>
		</div>
		<div class="blind">
			<h2>주의 하세요!</h2>
			<ul>
			<li>모든 경품의 제세공과금은 King 에서 부담합니다.</li>
			<li>등록한 사진과 내용은 본인의 페이스북 담벼락 및 현재 페이지에 보여집니다.</li>
			<li>괌, 사이판 가족여행권은 항공권, 숙박(조식포함), 여행자 보험이 포함 된 패키지이며, (주)HS Ad에 위탁되어 제공됩니다.</li>
			<li>경품은 사정에 따라 변경될 수 있으며, 경품 이미지는 실제와 다를 수 있고 당첨 된 경품 외, 다른 경품으로 변경이 불가합니다.</li>
			<li>본 이벤트 경품 당첨자는 추첨을 통해 선정되며, 당첨 공지는 당첨자 발표일에 King.com 공식 블로그에서 발표됩니다.(별도 게재)</li>
			<li>잘못된 정보 입력으로 피해가 발생했을 경우 King은 어떠한 책임도 부담하지 않습니다.</li>
			<li>타인의 개인정보를 입력 또는 허위 정보를 입력하여 이벤트에 참여하는 경우 모든 당첨이 취소될 수 있습니다.</li>
			<li>작성한 글이 허위, 비방, 욕설 글로 판단될 경우 예고없이 삭제될 수 있습니다. 기타 부정한 방법으로 인한 당첨 시 당첨이 취소됩니다.</li>
			</ul>
			<p>2015 &copy; king.com</p>
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