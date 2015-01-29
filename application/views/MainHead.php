<?php
$menu = array(array('preReserve','사전예약'),array('teaser','곰가족영상')); 
?>
<a href="<?php echo site_url()?>">로고</a>
<ul>
<?php for($i=0; $i<count($menu); $i++){
	$str = '<li>';
	if($this->router->fetch_class() == $menu[$i][0]){
		$str .= '<strong>'.$menu[$i][1].'</strong>';
	}else{
		$str .= '<a href="'.site_url($menu[$i][0]).'">'.$menu[$i][1].'</a>';
	}
	$str .= '</li>';
	echo $str;
}?>
</ul>

<ul>
	<li>공식블로그</li>
	<li>공식페이스북</li>
</ul>