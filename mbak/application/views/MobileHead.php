<div id="wrap">
	<!-- gnb -->
	<ul id="gnb">
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
	</ul>
	<!-- //gnb -->