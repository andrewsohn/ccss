<script type="text/javascript" src="<?php echo $this->config->item('asset_url');?>/PC/js/app/admin.js"></script>
<?php
if($this->router->fetch_class() == 'login'){?>
<!-- wrap -->
<div id="wrap" class="login">
<?php }else{ ?>
<!-- wrap -->
<div id="wrap">
	<!-- header -->
	<div id="gnb">
		<div class="head">
			<h1><a href="<?php echo site_url("admin")?>"><img src="<?php echo $this->config->item('asset_url');?>/admin/img/logo.png" alt="sscc"></a></h1>
			<ul>
			<li><a href="<?php echo site_url("logout")?>">로그아웃</a></li>
			<li><a href="<?php echo site_url()?>">사이트 바로가기</a></li>
			</ul>
		</div>
		
		<?php
		$i = 0;
		$pre_am_menu = 0;
		
		$gnb_str = '<ul class="nav">';
		
		for($i=0; $i<count($mlist); $i++){
			if($mlist[$i]->am_menu != $pre_am_menu){
				$pre_am_menu = $mlist[$i]->am_menu;
		
				if($i != 0) $gnb_str .= '</li>';
		
				$current_class = "";
				if (isset($am_code) && $am_code == $mlist[$i]->am_code) $current_class = 'class="now"';
		
				$gnb_str .= '<li '.$current_class.'>'.PHP_EOL;
		
				if ($mlist[$i]->am_code){
					if($mlist[$i]->am_id == 18){
						$gnb_str .=  '<a href="https://www.google.com/analytics/web/?hl=ko&pli=1#report/visitors-overview/a59155061w93267298p97151315/" target="_blank">' . $mlist[$i]->am_name . '</a>';
					}else{
						$gnb_str .=  '<a href="'.$baseUrl.'/'.$mlist[$i]->am_code.'">' . $mlist[$i]->am_name . '</a>';
					}
					
				}else{
					$gnb_str .= $mlist[$i]->am_name;
				}
			}else{
				$n = $i-1;
				$m = $i+1;
		
				if($mlist[$n]->am_order == '1' && $mlist[$i]->am_menu == $mlist[$n]->am_menu)
					$gnb_str .= "<ul>";
		
				$gnb_str .= '<li><a href="'.$baseUrl.'/'.$mlist[$i]->am_code.'">'.$mlist[$i]->am_name.'</a></li>';
		
				if($mlist[$i]->am_menu != $mlist[$m]->am_menu)
					$gnb_str .= '</ul>';
			}
		
		}
		$gnb_str .= "</ul>";
		echo $gnb_str;
		?>
	</div>
	<!-- //header -->
<?php 
}
?>