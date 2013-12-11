<?php
/**
 * Utilities - ApkStore Utilities class
 *     Provide static functions for general utilities
 * NOTE: 
 *     
 * @package ApkStore
 * @author wangpeifeng
 */

class Utilities
{
        /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////
    
    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////
    
    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////
    
    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////
    const EMAIL_REGULAR           = '/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/';
    const NUMBER_REGULAR          = '/^[1-9][0-9]*$/';
    
    /////////////////////////////////////////////////
    // METHODS
    /////////////////////////////////////////////////

    public static function generate_page_links($url, $sort,$order, $cur_page, $num_pages, $page_index_max)
    {
        $page_links = '';
        
        $page_links .= '<table><tr><td>';
        $page_links .= "总共".$num_pages."页：";
        
        if ($cur_page > 1) {
            $page_links .= '<a href="'.$url
                ."?sort=$sort&order=$order"
                ."&page=".($cur_page-1)
                .'"><前一页 </a>';
        }
        else{
            $page_links .= '<前一页 ';
        }
        
        $index_start = max(Array(1, $cur_page - ceil($page_index_max/2)));
        $index_end = min(Array($num_pages, $index_start + $page_index_max-1));

        if ($index_start > 1) {
            $page_links .= '<a href="'.$url
                ."?sort=$sort&order=$order"
                ."&page=1"
                .'"> 1 </a>'.' ... ';
        }
        
        for($i = $index_start; $i <= $index_end; $i++){
            if($cur_page == $i){
                $page_links .= ''.$i;
            }
            else {
                $page_links .= '<a href="'.$url
                    ."?sort=$sort&order=$order"
                    ."&page=$i"
                    .'"> '.$i.' </a>';
            }
        }
        
        if ($index_end < $num_pages) {
            $page_links .= ' ... <a href="'.$url
                ."?sort=$sort&order=$order"
                ."&page=$num_pages"
                .'"> '.$num_pages.' </a>';
        }
        
        if ($cur_page < $num_pages){
            $page_links .= '<a href="'.$url
                ."?sort=$sort&order=$order"
                ."&page=".($cur_page+1)
                .'"> 下一页></a>';
        }
        else {
            $page_links .= ' 下一页>';
        }
        
        $page_links .= '</td><td>';
        
        $page_links .= '<form method="GET" action="'.$_SERVER ['PHP_SELF'].'">';
        $page_links .= '<input id="page" name="page" type="text" value="'.$cur_page.'"></input>';
        $page_links .= '<input id="sort" name="sort" type="hidden" value="'.$sort.'"></input>';
        $page_links .= '<input id="order" name="order" type="hidden" value="'.$order.'"></input>';
        $page_links .= '<input type="submit" name="submit" value="GO!" />';

        $page_links .= '</td></tr></table>';
        
        
        return $page_links;
        
    }
    
    public static function generate_page_links_param($url, $sort,$order, $cur_page, $num_pages, $page_index_max,$params=false)
    {
    	$page_links = '';
    
    	$page_links .= '<table><tr><td>';
    	$page_links .= "总共".$num_pages."页：";
    
    	$paramsencode = "";
    	if($params){
    		foreach($params as $param){
    			$paramsencode .= "&".$param['name']."=".$param['value'];
    		}
    	}
    
    	if ($cur_page > 1) {
    		$page_links .= '<a href="'.$url
    		."?sort=$sort&order=$order".$paramsencode
    		."&page=".($cur_page-1)
    		.'"><前一页 </a>';
    	}
    	else{
    	$page_links .= '<前一页 ';
    	}
    
    	$index_start = max(Array(1, $cur_page - ceil($page_index_max/2)));
    	$index_end = min(Array($num_pages, $index_start + $page_index_max-1));
    
    	if ($index_start > 1) {
    	$page_links .= '<a href="'.$url
    	."?sort=$sort&order=$order".$paramsencode
    	."&page=1"
    	.'"> 1 </a>'.' ... ';
    	}
    
    	for($i = $index_start; $i <= $index_end; $i++){
    			if($cur_page == $i){
    			$page_links .= ''.$i;
            }
                else {
                $page_links .= '<a href="'.$url
                ."?sort=$sort&order=$order".$paramsencode
                	."&page=$i"
                	.'"> '.$i.' </a>';
    }
    }
    
    if ($index_end < $num_pages) {
    	$page_links .= ' ... <a href="'.$url
    	."?sort=$sort&order=$order".$paramsencode
    	."&page=$num_pages"
    	.'"> '.$num_pages.' </a>';
    }
    
    	if ($cur_page < $num_pages){
    			$page_links .= '<a href="'.$url
    			."?sort=$sort&order=$order".$paramsencode
    			."&page=".($cur_page+1)
    			.'"> 下一页></a>';
    			}
    					else {
    					$page_links .= ' 下一页>';
    			}
    
    			$page_links .= '</td><td>';
    
    			$page_links .= '<form method="GET" action="'.$_SERVER ['PHP_SELF'].'">';
    			$page_links .= '<input id="page" name="page" type="text" value="'.$cur_page.'"></input>';
    			$page_links .= '<input id="sort" name="sort" type="hidden" value="'.$sort.'"></input>';
    			$page_links .= '<input id="order" name="order" type="hidden" value="'.$order.'"></input>';
    			if($params){
    				foreach($params as $param){
    					$page_links .= '<input id="'.$param['name'].'" name="'.$param['name'].'" type="hidden" value="'.$param['value'].'"></input>';
    				}
    			}
    			 
    			$page_links .= '<input type="submit" name="submit" value="GO!" />';
    
        $page_links .= '</td></tr></table>';
    
    
            return $page_links;
    
    }
    
    public static function escapString($string)
		{
		    $escape =  preg_replace('/\\/', '\\\\', $string);
		    return  preg_replace("/'/","\\'",$escape);
		}
    
    public static function checkEmail($email)
    {
        if (!preg_match(self::EMAIL_REGULAR, $email)){
            return FALSE;
        }
        else{
            $domain = preg_replace(self::EMAIL_REGULAR, '', $email);
            if(!self::checkDNSRR($domain)){
                return FALSE;
            }
        }
        return TRUE;
    }

    
    public static function checkNumber($number)
    {
    	if (!preg_match(self::NUMBER_REGULAR, $number)){
    		return FALSE;
    	}
    	return TRUE;
    }
    
    /**
     * 
     * Check the availability of $domain 
     * @param string $domain
     * @param string $recType
     * 
     * @return boolean
     */
    public static function checkDNSRR($domain, $recType = 'ANY')
    {
        if(self::isWindows()){
            return self::win_checkdnsrr($domain,$recType);
        }
        else{
            return checkdnsrr($domain,$recType);
        }
    }

    private static function win_checkdnsrr($domain, $recType = '')
    {
        $result = false;
        if(!empty($domain)){
            if($recType == ''){
                $recType = 'MX';
            }
            exec("nslookup -type=$recType $domain", $output);
            foreach($output as $line){
                if(preg_match("/^$domain/", $line)){
                    $result = true;
                }
            }
        }
        return $result;
    }
    
    
    private static function isWindows()
    {
        if (stristr(php_uname(), 'Win')){
            return TRUE;
        }
        return FALSE;
    }
    
    public static function getNewLine2()
    {
        if (self::isWindows()){
            return "\r\n\r\n";
        }
        else{
            return "\n\n";
        }
    }
    
    public static function getNewLine()
    {
        if (self::isWindows()){
            return "\r\n";
        }
        else{
            return "\n";
        }
    }

		public static function convertFileName($file_name)
		{
		    if(self::isWindows()){
		        return mb_convert_encoding($file_name, "gb2312", "utf-8");
		    }
		    else{
		        return $file_name;
		    }
		}
		
		public static function checkUploadError($upload_error)
		{
        switch($upload_error){
        case 0:
            return "文件上传成功!";
        case 1:
            return "文件大小超出服务器空间限制！";
        case 2:
            return "文件大小超出浏览器限制！";
        case 3:
            return "文件仅部分被上传！";
        case 4:
            return "没有找到要上传的文件！";
        case 5:
            return "服务器临时文件丢失！";
        case 6:
            return "写入临时文件夹出错！";
        }
		    
		}
		
		
	public static function getPeriodArray($period_selected, $date_start, $date_end){
		$array = FALSE;
		
		$index = 0;
		
		$stamp_start = strtotime($date_start);
		$stamp_end = strtotime($date_end);
		
		$getdate = getdate($stamp_start);
		/*
		$year = date("Y",$date_start);
		$month = date("m",$date_start);
		$date = date("d",$date_start);
		*/
		$year = $getdate["year"];
		$month = $getdate["mon"];
		$date = $getdate["mday"];
		
		$date_now = $date_start;
		$stamp_now = $stamp_start;
		
		do{
			$array[$index]["stamp_start"] = date("Y-m-d H:i:s", $stamp_now);
			
			$getdate = getdate($stamp_now);
			$year = $getdate["year"]+0;
			$mon = $getdate["mon"]+0;
			
			
			
			switch ($period_selected+0){
			case 1://年
				if(date("L",$stamp_now)){
				//if(checkdate(2, 29, $year)){
					$ydays = 366;
				}
				else{
					$ydays = 365;
				}
				$yday = $getdate["yday"];
				$time_to_next = ($ydays-$yday)*24*3600;
				$stamp_new = $stamp_now + $time_to_next;
				$date_new = date("Y-m-d", $stamp_new);
				
				break;
			case 2://月
				/*
				if($mon==2){
					if(checkdate(2, 29, $year)){
						$mdays = 29;
					}
					else{
						$mdays = 28;
					}
				}
				else{
					if(checkdate($mon, 31, $year)){
						$mdays = 31;
					}
					else{
						$mdays = 30;
					}
				}
				*/
				$mdays=date("t",$stamp_now);
				$mday = $getdate["mday"]+0;
				$time_to_next = ($mdays-$mday+1)*24*3600;
				$stamp_new = $stamp_now + $time_to_next;
				$date_new = date("Y-m-d", $stamp_new);
								
				break;
			case 3://week
				//$wday = $getdate["wday"]+0;
				$wday = date("N",$stamp_now);
				
				$time_to_next = (7-$wday+1)*24*3600;
				$stamp_new = $stamp_now + $time_to_next;
				$date_new = date("Y-m-d", $stamp_new);
				
				break;
				
			case 4://day
				$time_to_next = 24*3600;
				$stamp_new = $stamp_now + $time_to_next;
				$date_new = date("Y-m-d", $stamp_new);

				break;
				
		}
		
		$date_now = $date_new;
		$stamp_now = $stamp_new;
		$array[$index]["stamp_end"] = date("Y-m-d H:i:s", $stamp_now);
		$index++;
		}
		while($stamp_now<=$stamp_end);
		
		$stamp_now = $stamp_end;
		if ($index>1) {
			$array[$index-1]["stamp_end"] = date("Y-m-d H:i:s", $stamp_now+24*3600);
		}
		else{
			$array[0]["stamp_end"] = date("Y-m-d H:i:s", $stamp_now+24*3600);
		}
			
			
		return $array;
	}
	
	public static function getAreaArray($area_level, $province_selected_serial, $city_selected_serial){
		$array = FALSE;

		switch($area_level){
			case 1://province
				require_once '../utilities/class.provincemanager.php';
				$provinceMgr = new ProvinceManager();
				if($province_selected_serial>0){
					$array[0] = $provinceMgr->getProvince($province_selected_serial);
				}
				else{
					$array = $provinceMgr->getProvinceArray();
				}
				
				break;
				
				
			case 2://city
				require_once '../utilities/class.citymanager.php';
				$cityMgr = new CityManager();
				if($city_selected_serial>0){
					$array[0] = $cityMgr->getCity($city_selected_serial);
				}
				else{
					$array = $cityMgr->getCityArray($province_selected_serial);
				}
				break;
		}
			
		return $array;
	}
	
	
	public function draw_bar_array($weidth, $height, $data, $max_value,$average_value, $filename,  $left,$top, $right, $bottom)
	{
		$image = imagecreate($weidth+$left+$right, $height+$top+$bottom);
		$x0 = $left;
		$y0 = $top + $height;
		$MAX = 255;
		$LIGHT = 192;
		$DARK = 128;
		$MIN = 0;
		$bg_color = imagecolorallocate($image, $MAX, $MAX, $MAX);
		$text_color = imagecolorallocate($image, $MAX, $MIN, $MIN);
		$bar_color = imagecolorallocate($image, $MIN, $DARK, $DARK);
		$border_color = imagecolorallocate($image, $DARK, $DARK, $DARK);
		//$scale_color = imagecolorallocate($image, $MIN, $MIN, $MIN);
	
		imagefilledrectangle($image, $x0, $y0, $x0+$left, $top, $bg_color);
		imageline($image, $x0, $y0, $x0+$weidth+$right, $y0, $border_color);
		imageline($image, $x0, $y0, $x0, $y0-$height-$top, $border_color);
	
		imageline($image, $x0, $y0-$height, $x0+$weidth+$right, $y0-$height, $border_color);
		imagettftext($image, 16,
		0,
		0  ,
		$y0-$height,
		$text_color,
		"../res/huawen.ttf",
		$max_value);
	
		imageline($image, $x0, $y0-$average_value/$max_value*$height, $x0+$weidth+$right, $y0-$average_value/$max_value*$height, $border_color);
		imagettftext($image, 16,
		0,
		0  ,
		$y0-$average_value/$max_value*$height,
		$text_color,
		"../res/huawen.ttf",
		$average_value);
	
	
		$bar_weidth = $weidth / (count($data)*2 + 1);
		$font_size = 24;
		if ($font_size > $bar_weidth -4 ){
			$font_size = $bar_weidth -4 ;
		}
		$l = count($data);
		for ($i=0; $i<$l; $i++){
			$j = $l-1-$i;
			imagefilledrectangle($image, $x0 + ($i*$bar_weidth*2)+$bar_weidth,
			$y0,
			$x0 + (($i+1)*$bar_weidth*2),
			$y0 - ($height/$max_value*($data[$j]['num'])), $bar_color);
	
			imagettftext($image, $font_size,
			90,
			$x0 + ($i*$bar_weidth*2)+$bar_weidth*2 -2 ,
			$y0 - 5,
			$text_color,
			"../res/huawen.ttf",
			$data[$j]['name']);
	
			imagettftext($image, 24,
			0,
			$x0 + ($i*$bar_weidth*2)+$bar_weidth ,
			$y0 + 5 +24,
			$text_color,
			"../res/huawen.ttf",
			$data[$j]['scale']);
		}
	
		imagepng($image,$filename,5);
		imagedestroy($image);
	
	}
	
}

?>