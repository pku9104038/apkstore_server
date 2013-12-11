<?php
header("content-type;text/html;charset=utf-8");
function ipDecode($queryIP){
	$url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;
	
	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // ��ȡ��ݷ���
	$result = curl_exec($ch);
	$result = mb_convert_encoding($result, "utf-8", "gb2312"); // ����ת������������
	curl_close($ch);
	preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
	if (isset($ipArray[1])){
		$loc = $ipArray[1];
	}
	else{
		$loc = "未知";
	
	}
	return $loc;
	
}

function getProvince($ipDecoded){
	$result = false;
	if(strstr($ipDecoded,"中国")){
		$string = substr($ipDecoded, 6);
		$offset = stripos($string, "省");
		if(!$offset){
			$offset = stripos($string, "区");
		}
		if(!$offset){
			$offset = stripos($string, "市");
		}
		$result[0] = substr($string,0, $offset+3);
		$result[1] = substr($string,$offset+3);
	}
	return $result;
	
}

function getCity($ipDecoded){
	$result = false;
	$string = $ipDecoded;
		$offset = stripos($string, "市");
		if(!$offset){
			$offset = stripos($string, "区");
		}
		
		$result[0] = substr($string,0,$offset ? $offset+3 : 0);
		$result[1] = substr($string,$offset ? $offset+3 : 0);
	return $result;
	
}

function getIPLoc($queryIP)
{
	$url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;

	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // ��ȡ��ݷ���
	$result = curl_exec($ch);
	$result = mb_convert_encoding($result, "utf-8", "gb2312"); // ����ת������������
	curl_close($ch);
	preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
	if (isset($ipArray[1])){
		$loc = $ipArray[1];
	}
	else{
		$loc = "未知";

	}
	//return $loc;

	$provinces = Array(
			'北京',
			'上海',
			'天津',
			'重庆',
			'黑龙江',
			'吉林',
			'辽宁',
			'内蒙古',
			'河北',
			'山西',
			'陕西',
			'甘肃',
			'宁夏',
			'青海',
			'新疆',
			'西藏',
			'四川',
			'贵州',
			'云南',
			'河南',
			'山东',
			'江苏',
			'安徽',
			'浙江',
			'福建',
			'江西',
			'湖北',
			'湖南',
			'广东',
			'广西',
			'海南',
			'台湾',
			'香港',
			'澳门',
			'未知'
	);
	$result = "未知";
	foreach ($provinces as $province){
		if (strstr($loc, $province )){
			$result = $province;
			break;
		}
	}

	return $result;

}

function getIPCity($queryIP)
{
	$url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;

	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // ��ȡ��ݷ���
	$result = curl_exec($ch);
	$result = mb_convert_encoding($result, "utf-8", "gb2312"); // ����ת������������
	curl_close($ch);
	preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
	$loc = "未知";
	if (isset($ipArray[1])){
		preg_match("中国(.*)&nbsp",$ipArray[1],$countryArray);
		if (isset($countryArray[1])){
			preg_match("中国(.*)[省区]",$countryArray[1],$provinceArray);
			preg_match("[省区](.*)(市|地区)",$countryArray[1],$cityArray);
			if(isset($provinceArray[1])){
				$province = $provinceArray[1];
				if(isset($cityArray[1])){
					$city = $cityArray[1];
				}
			}
			else {
				preg_match("中国(.*)市",$countryArray[1],$provinceArray);
				preg_match("中国(.*)市",$countryArray[1],$cityArray);
				if(isset($provinceArray[1])){
					$province = $provinceArray[1];
					if(isset($cityArray[1])){
						$city = $cityArray[1];
					}
				}
			}
			$loc = $province."=>".$city;
		}
	}
	return $loc;

}


	$ip = "211.137.59.23";
	$ipDecode = ipDecode($ip);
	$province = getProvince($ipDecode);
	$city = getCity($province[1]);
	
	echo $ip." decoded: ".$ipDecode." from province :".$province[0]." city:".$city[0];
?>