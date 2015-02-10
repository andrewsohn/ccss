<div id="wrap">
	<!-- gnb -->
	<ul id="gnb">
	<li class="logo"><a href="<?php echo site_url()?>"><span>candy crush soda</span></a></li>
	<?php for($i=0; $i<count($menu); $i++){
		$num = $i+1;
		$str = '<li';
		if($this->router->fetch_class() == $menu[$i]->me_code){
			$str .= ' class="on">';
		}else{
			$str .= '>';
		}
		$sc='';
		if($menu[$i]->me_id == 8 || $menu[$i]->me_id == 7){
			$sc=' _construct';
		}
		if($menu[$i]->me_id == 8){
			$str .= '<a href="#" class="movie'.$sc.'"><span>'.$menu[$i]->me_name.'</span></a>';
		}else{
			$str .= '<a href="'.site_url().'/'.$menu[$i]->me_code.'" class="ev'.$num.$sc.'"><span>'.$menu[$i]->me_name.'</span></a>';
		}
		
		$str .= '</li>';
		echo $str;
	}?>
	<li class="go">
		<ul>
		<li><a href="#" onclick="openMainShare(this);" class="fb"><span>공식 페이스북</span></a></li>
		<li><a href="#" onclick="openMainShare(this);" class="tt"><span>공식 트위터</span></a></li>
		</ul>
	</li>
	</ul>
	<!-- //gnb -->
	
