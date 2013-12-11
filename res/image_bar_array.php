<?php
$MAX = 255;
$LIGHT = 192;
$DARK = 128;
$MIN = 0;
$bg_c = Array("red"=>$MAX,"green"=>$MAX,"blue"=>$MAX);
$text_c = Array("red"=>$MAX,"green"=>$MIN,"blue"=>$MIN);
$bar_c = Array("red"=>$MIN,"green"=>$DARK,"blue"=>$DARK);
$border_c = Array("red"=>$DARK,"green"=>$DARK,"blue"=>$DARK);

$margin = 20;
$width = 960;
$height = 480;

function draw_bar_graph_num($width, $height, $margin, $data, $bg_c, $text_c, $bar_c, $border_c)
{
	
	$left = $margin;
	$top = $margin;
	$right = $margin;
	$bottom = $margin;
	
	
	
	$image = imagecreate($width+$left+$right, $height+$top+$bottom);
	$x0 = $left;
	$y0 = $top + $height;
	
	//$scale_color = imagecolorallocate($image, $MIN, $MIN, $MIN);
	
	$bg_color = imagecolorallocate($image, $bg_c["red"], $bg_c["green"], $bg_c["blue"]);
	$text_color = imagecolorallocate($image, $text_c["red"], $text_c["green"], $text_c["blue"]);
	$bar_color = imagecolorallocate($image, $bar_c["red"], $bar_c["green"], $bar_c["blue"]);
	$border_color = imagecolorallocate($image, $border_c["red"], $border_c["green"], $border_c["blue"]);
	
	
	imagefilledrectangle($image, $x0, $y0, $x0+$left, $top, $bg_color);
	imageline($image, $x0, $y0, $x0+$width, $y0, $border_color);
	imageline($image, $x0, $y0, $x0, $y0-$height, $border_color);
	imageline($image, $x0+$width, $y0, $x0+$width, $y0-$height, $border_color);
	
	$max_value=0;
	$value_total = 0;
	for($i=0; $i<count($data); $i++ ){
		if ($max_value<$data[$i]["value"]) {
			$max_value = $data[$i]["value"];
		}
		$value_total += $data[$i]["value"];
	}
	$average_value = ceil($value_total/count($data));
	

	imageline($image, $x0, $y0-$height, $x0+$width, $y0-$height, $border_color);
	/*
	imagettftext($image, 16,
	0,
	0  ,
	$y0-$height,
	$text_color,
	"../res/huawen.ttf",
	$max_value);
*/
	imageline($image, $x0, $y0-$average_value/$max_value*$height, $x0+$width, $y0-$average_value/$max_value*$height, $border_color);
	/*
	imagettftext($image, 16,
	0,
	0  ,
	$y0-$average_value/$max_value*$height,
	$text_color,
	"../res/huawen.ttf",
	$average_value);
*/

	
	$bar_width = $width / (count($data)*2 + 1);
	
	if($bar_width > 30){
		$bar_width = 30;
	}
	else if($bar_width < 12){
		
	}
	
	$column_width = $width / (count($data)* + 1);
	if($column_width > 60){
		$column_width = 60;
	}
	$bar_width = $column_width/2;
	$gap_width = $column_width/2;
	
	if ($column_width < 20 ) {
		$gap_width = 4;
		$bar_width = $column_width -$gap_width;
	}
	
	$font_size = $bar_width -4;
	
	$l = count($data);
	for ($i=0; $i<$l; $i++){
		$j = $l-1-$i;
		imagefilledrectangle($image, $x0 + ($i*($bar_width+$gap_width))+$gap_width,
		$y0,
		$x0 + (($i+1)*($bar_width+$gap_width)),
		$y0 - ($height/$max_value*($data[$i]['value'])), $bar_color);

		imagettftext($image, $font_size,
		90,
		$x0 + (($i+1)*($bar_width+$gap_width)) -3 ,
		$y0 - 5,
		$text_color,
		"../res/huawen.TTF",
		$data[$i]['name']);

	}
	
	return $image;

}

$image = draw_bar_graph_num($width, $height, $margin, $array, $bg_c, $text_c, $bar_c, $border_c);
imagepng($image,$pngfile,5);

imagedestroy($image);

?>