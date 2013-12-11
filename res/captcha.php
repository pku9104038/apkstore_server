<?php

session_start();

// set some captcha constants
define('CAPTCHA_NUMCHARS', 6);
define('CAPTCHA_WIDTH', 130);
define('CAPTCHA_HEIGHT', 30);
define('ASCII_a', 97);
define('ASCII_z', 122);
define('DOTS_NUM', 300);
define('LINE_NUM', 2);
define('FONT_FILE', "../fonts/Courier New Bold.ttf");
define('FONT_SIZE', 18);
define('FONT_ANGLE', 30);
define('FONT_PADDING', 10);
define('FONT_WIDTH', 20);
define('FONT_WAVE', 3);
define('COLOR_BACKGOUND', 196);
define('COLOR_DOT', 196);
define('COLOR_TEXT', 96);
define('COLOR_LINE', 128);
define('COLOR_BLACK', 0);

// generate the random pass-phrase
//$pass_phrase = "";

$captcha = "";
for($i=0;$i<CAPTCHA_NUMCHARS;$i++){

	$pass_phrase[$i] = chr(rand(ASCII_a, ASCII_z));

	$captcha .= $pass_phrase[$i];
	
}

//store the encrypted pass_phrase in a session variable

$_SESSION['captcha']=sha1($captcha);

//create the image
$img = imagecreatetruecolor(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);

// fill the background
$bg_color = imagecolorallocate($img, COLOR_BACKGOUND, COLOR_BACKGOUND, COLOR_BACKGOUND);//gray

imagefilledrectangle($img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color);

// draw some random line

for($i=0; $i<LINE_NUM; $i++){

	$graphic_color = imagecolorallocate($img, rand(COLOR_BLACK, COLOR_LINE), rand(COLOR_BLACK, COLOR_LINE), rand(COLOR_BLACK, COLOR_LINE));
	
	imageline($img, 0, rand()%CAPTCHA_HEIGHT, CAPTCHA_WIDTH, rand()%CAPTCHA_HEIGHT, $graphic_color);

}

// sprinkle in some random dots
for($i=0; $i<DOTS_NUM; $i++){

	$graphic_color = imagecolorallocate($img, rand(COLOR_BLACK, COLOR_DOT), rand(COLOR_BLACK, COLOR_DOT), rand(COLOR_BLACK, COLOR_DOT));
	
	imagesetpixel($img, rand()%CAPTCHA_WIDTH, rand()%CAPTCHA_HEIGHT, $graphic_color);

}

// draw the pass_phrase string
for($i=0;$i<CAPTCHA_NUMCHARS;$i++){

	$text_color = imagecolorallocate($img, rand(COLOR_BLACK, COLOR_TEXT), rand(COLOR_BLACK, COLOR_TEXT), rand(COLOR_BLACK, COLOR_TEXT));

	imagettftext($img, FONT_SIZE, rand(0-FONT_ANGLE,0+FONT_ANGLE), FONT_PADDING+$i*FONT_WIDTH , CAPTCHA_HEIGHT - FONT_PADDING + rand(0 - FONT_WAVE ,0 + FONT_WAVE), $text_color, FONT_FILE, $pass_phrase[$i]);
	
}


// out put image as a png using a header

header("Content-type:image/png");
imagepng($img);

// clean up
imagedestroy($img);

?>