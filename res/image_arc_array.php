<?php
//+------------------------+ 
//| pie3dfun.PHP//公用函数 | 
//+------------------------+ 
define("ANGLE_STEP", 3); //定义画椭圆弧时的角度步长 
define("FONT_USED", "../res/huawen.TTF"); // 使用到的字体文件位置 

$color_array = Array(
		0xff0000,//'北京',
		0x00ff00,//'上海',
		0x0000ff,//'天津',
		0xffff00,//'重庆',
		0x00ffff,//'黑龙江',
		0xff00ff,//'吉林',
		0x99ff00,//'辽宁',
		0x0099ff,//'内蒙古',
		0xff0099,//'河北',
		0x33ff33,//'山西',
		0xff3333,//'陕西',
		0x3333ff,//'甘肃',
		0xff6666,//'宁夏',
		0x66ff66,//'青海',
		0x6666ff,//'新疆',
		0xff6600,//'西藏',
		0x00ff66,//'四川',
		0x6600ff,//'贵州',
		0xff3300,//'云南',
		0x00ff33,//'河南',
		0x3300ff,//'山东',
		0xff9966,//'江苏',
		0x66ff99,//'安徽',
		0x9966ff,//'浙江',
		0xff33ff,//'福建',
		0xffff33,//'江西',
		0x33ffff,//'湖北',
		0xff66ff,//'湖南',
		0xffff66,//'广东',
		0x66ffff,//'广西',
		0xff99ff,//'海南',
		0xffff99,//'台湾',
		0x99ffff,//'香港',
		0xff9933//'澳门'//,
		//        '未知'
);

function draw_getdarkcolor($img,$clr) //求$clr对应的暗色 
{ 
    $rgb = imagecolorsforindex($img,$clr); 
    return array($rgb["red"]/2,$rgb["green"]/2,$rgb["blue"]/2); 
} 

function draw_getexy($a, $b, $d) //求角度$d对应的椭圆上的点坐标 
{ 
    $d = deg2rad($d); 
    return array(round($a*Cos($d)), round($b*Sin($d))); 
} 

function draw_arc($img,$ox,$oy,$a,$b,$sd,$ed,$clr) //椭圆弧函数 
{ 
    $n = ceil(($ed-$sd)/ANGLE_STEP); 
    $d = $sd; 
    list($x0,$y0) = draw_getexy($a,$b,$d); 
    for($i=0; $i<$n; $i++) 
    { 
        $d = ($d+ANGLE_STEP)>$ed?$ed:($d+ANGLE_STEP); 
        list($x, $y) = draw_getexy($a, $b, $d); 
        imageline($img, $x0+$ox, $y0+$oy, $x+$ox, $y+$oy, $clr); 
        $x0 = $x; 
        $y0 = $y; 
    } 
} 

function draw_sector($img, $ox, $oy, $a, $b, $sd, $ed, $clr) //画扇面 
{ 
    $n = ceil(($ed-$sd)/ANGLE_STEP); 
    $d = $sd; 
    list($x0,$y0) = draw_getexy($a, $b, $d); 
    imageline($img, $x0+$ox, $y0+$oy, $ox, $oy, $clr); 
    for($i=0; $i<$n; $i++) 
    { 
        $d = ($d+ANGLE_STEP)>$ed?$ed:($d+ANGLE_STEP); 
        list($x, $y) = draw_getexy($a, $b, $d); 
        imageline($img, $x0+$ox, $y0+$oy, $x+$ox, $y+$oy, $clr); 
        $x0 = $x; 
        $y0 = $y; 
    } 
    imageline($img, $x0+$ox, $y0+$oy, $ox, $oy, $clr); 
    list($x, $y) = draw_getexy($a/2, $b/2, ($d+$sd)/2); 
    imagefill($img, $x+$ox, $y+$oy, $clr); 
} 

function draw_sector3d($img, $ox, $oy, $a, $b, $v, $sd, $ed, $clr) //3d扇面 
{ 
    draw_sector($img, $ox, $oy, $a, $b, $sd, $ed, $clr); 
    if($sd<180) 
    { 
        list($R, $G, $B) = draw_getdarkcolor($img, $clr); 
        $clr=imagecolorallocate($img, $R, $G, $B); 
        if($ed>180) $ed = 180; 
        list($sx, $sy) = draw_getexy($a,$b,$sd); 
        $sx += $ox; 
        $sy += $oy; 
        list($ex, $ey) = draw_getexy($a, $b, $ed); 
        $ex += $ox; 
        $ey += $oy; 
        imageline($img, $sx, $sy, $sx, $sy+$v, $clr); 
        imageline($img, $ex, $ey, $ex, $ey+$v, $clr); 
        draw_arc($img, $ox, $oy+$v, $a, $b, $sd, $ed, $clr); 
        list($sx, $sy) = draw_getexy($a, $b, ($sd+$ed)/2); 
        $sy += $oy+$v/2; 
        $sx += $ox; 
        imagefill($img, $sx, $sy, $clr); 
    } 
} 

function draw_getindexcolor($img, $clr) //RBG转索引色 
{ 
    $R = ($clr>>16) & 0xff; 
    $G = ($clr>>8)& 0xff; 
    $B = ($clr) & 0xff; 
    return imagecolorallocate($img, $R, $G, $B); 
} 

// 绘图主函数，并输出图片 
// $datLst 为数据数组, $datLst 为标签数组, $datLst 为颜色数组 
// 以上三个数组的维数应该相等 
function draw_img($datLst,$labLst,$clrLst,$filename,$a=300,$b=200,$v=20,$font=14) 
{ 
    $left = 20;
    $top = 50;
    $right = 50;
    $bottom = 50;
    $fw = 12;//imagefontwidth($font); 
    $fh = 16;//imagefontheight($font); 
    $label_weidth = 250;
    $n = count($datLst);//数据项个数 
    $w = 10+$a*2 + $label_weidth*2 +$left + $right ; 
    $h = 10+$b*2 + $v+($fh+2)*$n;
    $h1 = 10+$b*2;
    $h2 = $v+($fh+2)*$n;
    if($h2>$h1){
        $h=$h2+$top+$bottom; 
    }
    else{
        $h = $h1+$top+$bottom;
    }
    
    $img = imagecreate($w, 540); 
    
    $ox = 5+$a + $label_weidth + $left; 
    $oy = 5+$b + $top; 
    
    //转RGB为索引色 
    
    for($i=0; $i<$n; $i++) 
        $clrLst[$i] = draw_getindexcolor($img,$clrLst[$i]); 
    $clrbk = imagecolorallocate($img, 0xff, 0xff, 0xff); 
    $clrt = imagecolorallocate($img, 0, 0, 0); 
    //填充背景色 
    imagefill($img, 0, 0, $clrbk); 
    //求和 
    $tot = 0; 
    for($i=0; $i<$n; $i++) 
        $tot += $datLst[$i]; 
    $sd = 0; 
    $ed = 0; 
    $ly = 10;//+$b*2+$v; 
    $ly += $fh+2; 
    for($i=0; $i<$n; $i++) 
    { 
        $sd = $ed; 
        $ed += $datLst[$i]/$tot*360; 
        //画圆饼 
        draw_sector3d($img, $ox, $oy, $a, $b, $v, $sd, $ed, $clrLst[$i]); //$sd,$ed,$clrLst[$i]); 
        //画标签 
    
        $tag_left = 5+$left;
        $tag_top = $ly +$top;
        if ($i>intval(round($n/2))){
            $tag_left += $a*2 + 300;
            $tag_top = $ly - $fh*intval(round($n/2));
        }
        imagefilledrectangle($img, $tag_left, $tag_top, $tag_left+$fw, $tag_top+$fh, $clrLst[$i]); 
        imagerectangle($img, $tag_left, $tag_top, $tag_left+$fw, $tag_top+$fh, $clrt); 
        //imagestring($img, $font, 5+2*$fw, $ly, $labLst[$i].":".$datLst[$i]."(".(round(10000*($datLst[$i]/$tot))/100)."%)", $clrt); 
        //$str = iconv("GB2312", "UTF-8", $labLst[$i]); 
        $str = $labLst[$i];
        $label_left = 5+2*$fw+$left;
        $lable_top = $ly+13+$top;
        if ($i>intval(round($n/2))){
            $label_left += $a*2 + 300;
            $lable_top = $ly+13 - $fh*intval(round($n/2));
        }
        ImageTTFText($img, $font, 0, $label_left , $lable_top, $clrt, FONT_USED, $str.":".$datLst[$i]."(".(round(10000*($datLst[$i]/$tot))/100)."%)"); 
        $ly += $fh+2; 
    } 
    //输出图形 
    //header("Content-type: image/png"); 
    //输出生成的图片 
    //imagepng($img); 
    
	return $img;
    

} 

/*
$datLst = array(30, 20, 20, 20, 10, 20, 10, 20); //数据 
$labLst = array("浙江省", "广东省", "上海市", "北京市", "福建省", "江苏省", "湖北省", "安徽省"); //标签 
$clrLst = array(0x99ff00, 0xff6666, 0x0099ff, 0xff99ff, 0xffff99, 0x99ffff, 0xff3333, 0x009999); 
*/

foreach ($array as $key => $row) {
	$value[$key]  = $row['value'];
	$name[$key] = $row['name'];
}

// 将数据根据 volume 降序排列，根据 edition 升序排列
// 把 $data 作为最后一个参数，以通用键排序
array_multisort($value, SORT_DESC, $name, SORT_ASC, $array);


$i=0;
$j=0;
$s = count($color_array);

foreach ($array as $sector){
    $datLst[$i] = $sector['value']; 
    $labLst[$i] = $sector['name']; 
    $clrLst[$i] = $color_array[$j]; 
    $i++;
    $j++;
    if($j>=$s){
    	$j = 0;
    }
}

function set_color($i){
	
	$base = 0x22;
	$gap = 0x99;
	$full = 0xFF;
	
	$a = $i+1;
	$a = $a*$base;
	if($a>$full){
		$a = $a%$full;
	}
	if($a>$gap){
		$b=$a-$gap;
	}
	else{
		$b = $full - ($gap - $a);
	}
	
	if($b>$gap){
		$c = $b-$gap;
	}
	else{
		$c = $full - ($gap - $b);
	}
	return ($a<<16) + ($b<<8) + $c; 
}

//画图 
//$filename = '../images/pie_province.png'; 
unlink($pngfile);
$img = draw_img($datLst,$labLst,$clrLst,$pngfile); 

imagepng($img,$pngfile,5);
imagedestroy($img);


?>
