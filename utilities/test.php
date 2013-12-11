<?php 
function getRemoteIP(){

	$ip=false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if($ip){
			array_unshift($ips, $ip); $ip = FALSE;
		}
		for($i = 0; $i < count($ips); $i++){
			//if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])){
			if (!preg_match("/^(10|172\.16|192\.168)\./", $ips[$i])){
				$ip = $ips[$i];
				break;
			}
		}
	}
	return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
function ipDecode($queryIP){
	$url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;

	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
	$result = curl_exec($ch);
	$result = mb_convert_encoding($result, "utf-8", "gb2312"); 
	curl_close($ch);
	preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
	if (isset($ipArray[1])){
		$loc = $ipArray[1];
		$loc = preg_replace('/&nbsp.*/', "", $loc);
	}
	else{
		$loc = false;

	}
	
	return $loc;

}

function getProvince($ipDecoded){
	$result = false;
	if(strstr($ipDecoded,"中国")){
		$string = substr($ipDecoded, 6);
		$offset = stripos($string, "省");
		if(!$offset){
			if(strstr($string,"广西") 
				|| strstr($string,"宁夏")
				|| strstr($string,"新疆")
				|| strstr($string,"西藏")
				){
				$offset = 3;
			}
			elseif (strstr($string,"内蒙古")){
				$offset = 6;
			}
			//$offset = stripos($string, "区");
		}
		
		if(!$offset){
			$offset = stripos($string, "市");
		}
		/*
		$result[0] = substr($string,0, $offset+3);
		$city = getCity(substr($string,$offset+3));
		*/
		
		$result[0] = substr($string,0, $offset+3);
		$city = getCity(substr($string,$offset+3));
		
		if(!$city){
			$result[1] = $result[0];
		}
		else{
			$result[1] = $city;
		}
	}
	else{
		$result[0] = "海外";
		$result[1] = $ipDecoded;
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
	if(!$offset){
		$offset = stripos($string, "州");
	}
	if(!$offset){
		$offset = stripos($string, "盟");
	}
	if(!$offset){
		$offset = stripos($string, " ");
	}
	
	if(!$offset){
		;		
	}
	else{
		$result = substr($string,0,$offset+3);
	}
	return $result;

}


if(isset($_REQUEST["ip"])){
	$ip = $_REQUEST["ip"];
}
else{
	$ip = getRemoteIP();
}
$ipDecode = ipDecode($ip);
if($ipDecode){
	$provinceDecode = getProvince($ipDecode);
	if($provinceDecode){
		$province = $provinceDecode[0];
		$city = $provinceDecode[1];				
	}
}
else{
	$province = "未知";
	$city = "未知";
}

$offset = stripos($ipDecode, " ");
$country = substr($ipDecode,0, $offset);
$country = preg_replace('/&nbsp.*/', "", $ipDecode);
echo $ip."@".$ipDecode."@国家：".$country."@省份：".$province."@城市：".$city;

?>
<html>
<body>
<form name="frm">
    <select name="s1" onChange="redirec（document.frm.s1.options.selectedIndex）">
    <option selected>请选择</option>
    <option value="1">脚本语言</option>
    <option value="2">高级语言</option>
    <option value="3">其他语言</option>
    </select>
    <select name="s2">
    <option value="请选择" selected>请选择</option>
    </select>
    </form>
    <script language="javascript">
    //获取一级菜单长度
    var select1_len = document.frm.s1.options.length;
    var select2 = new Array（select1_len）；
    //把一级菜单都设为数组
    for （i=0; i<select1_len; i++）
    {
    select2[i] = new Array（）；
    }
    //定义基本选项
    select2[0][0] = new Option（"请选择", " "）；
    select2[1][0] = new Option（"PHP", " "）；
    select2[1][1] = new Option（"ASP", " "）；
    select2[1][2] = new Option（"JSP", " "）；
    select2[2][0] = new Option（"C/C++", " "）；
    select2[2][1] = new Option（"Java", " "）；
    select2[2][2] = new Option（"C#", " "）；
    select2[3][0] = new Option（"Perl", " "）；
    select2[3][1] = new Option（"Ruby", " "）；
    select2[3][2] = new Option（"Python", " "）；
    //联动函数
    function redirec（x）
    {
    var temp = document.frm.s2;
    for （i=0;i<select2[x].length;i++）
    {
    temp.options[i]=new Option（select2[x][i].text,select2[x][i].value）；
    }
    temp.options[0].selected=true;
    }
    </script>
    </body>
</html>
    