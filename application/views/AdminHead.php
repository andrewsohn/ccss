<?php
echo $member->mb_name."님 안녕하세요!";
echo '<br>';

$i = 0;
$pre_am_menu = 0;

$gnb_str = "<ul>";

for($i=0; $i<count($mlist); $i++){
	if($mlist[$i]->am_menu != $pre_am_menu){
		$pre_am_menu = $mlist[$i]->am_menu;
		
		if($i != 0) $gnb_str .= '</li>';
		
		$current_class = "";
		if (isset($am_code) && $am_code == $mlist[$i]->am_code) $current_class = " now";
		
		$gnb_str .= '<li class="m'.($i+1).$current_class.'">'.PHP_EOL;
		
		if ($mlist[$i]->am_code)
			$gnb_str .=  '<a href="'.$baseUrl.'/'.$mlist[$i]->am_code.'">' . $mlist[$i]->am_name . '</a>';
		else
			$gnb_str .= $mlist[$i]->am_name;
	}else{
		$n = $i-1;
		$m = $i+1;
		
		if($mlist[$n]->am_order == '1' && $mlist[$i]->am_menu == $mlist[$n]->am_menu)
			$gnb_str .= "<div class=\"depth2\"><ul><li>";
		 
		$gnb_str .= '<a href="'.$baseUrl.'/'.$mlist[$i]->am_code.'">'.$mlist[$i]->am_name.'</a>';
		 
		if($mlist[$i]->am_menu != $mlist[$m]->am_menu)
			$gnb_str .= '</li></ul></div>';
	}
	
}
$gnb_str .= "</ul>";
echo $gnb_str;
?>
<!-- [D] 2depth 메뉴가 있으면 1dpeth 오버시 class on, .depth2 영역 벗어나면 class on 제거-->
