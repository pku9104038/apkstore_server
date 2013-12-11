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
		}
		
		if(!$offset){
			$offset = stripos($string, "市");
		}
		
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


require_once 'api_constants.php';
require_once '../utilities/class.brandmanager.php';
require_once '../utilities/class.modelmanager.php';
require_once '../utilities/class.devicemanager.php';

$devsn = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_SN]+0;
$imei = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_IMEI];
if(isset($_REQUEST["query_imei"])){
	$imei = $_REQUEST["query_imei"];
}

$mgr = new DeviceManager();
$dev_serial = $mgr->getDeviceSN($imei);


if ($dev_serial<1 ){

	$brand = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_BRAND];
	$model = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_MODEL];
	$sdk = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_SDK]+0;
	$package = $_REQUEST[API_CONSTANTS::API_PARAM_APP_PACKAGE];
	$vercode = $_REQUEST[API_CONSTANTS::API_PARAM_APP_VEZRCODE]+0;

	$mgr = new DeviceManager();
	$devsn = $mgr->getDeviceSN($imei);
	if(devsn <1){
		$mgr = new BrandManager();
		$brand_serial = $mgr->getBrandSerial($brand);
		if($brand_serial <1){
			if($mgr->addBrand($brand)){
				$brand_serial = $mgr->getBrandSerial($brand);
			}
		}
		if($brand_serial > 0){
			$customer_serial = $mgr->getBrandCustomerSerial($brand_serial); 
				
			$mgr = new ModelManager();
			$model_serial = $mgr->getModelSerial($model, $brand_serial);
			if($model_serial==0){
				if($mgr->addModelAuto($model, $brand_serial,$customer_serial)){
					$model_serial = $mgr->getModelSerial($model, $brand_serial);
				}
			}
			if($model_serial>0){
				$mgr = new DeviceManager();
				$ip = getRemoteIP();
				$ipDecode = ipDecode($ip);
				if($ipDecode){
					$provinceDecode = getProvince($ipDecode);
					if($provinceDecode){
						$province = $provinceDecode[0];
						$city = $provinceDecode[1];				
					}
					
					require_once '../utilities/class.provincemanager.php';
					$provinceMgr = new ProvinceManager();
					require_once '../utilities/class.citymanager.php';
					$cityMgr = new CityManager();
					
					$province_serial = $provinceMgr->getProvinceSN($province);
					if ($province_serial==0) {
						$province_serial = $provinceMgr->addProvince($province);
					}
					if ($province_serial>0) {
						$city_serial = $cityMgr->getCitySN($city);
						if ($city_serial==0) {
							$city_serial = $cityMgr->addCity($city, $province_serial);
						}
						 
					}
										
				}
				else{
					$province = "未知";
					$city = "未知";
				}
				$devsn = $mgr->addDevice($imei, $model_serial, $package, $vercode, $sdk, $ip, $province, $city);

			}
		}
	}
}
else{
	$devsn = $dev_serial;

}

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$resp = FALSE;

if($devsn > 0){
	$resp = TRUE;
	$mgr = new DeviceManager();
	$date = $mgr->getDeviceDate($devsn);
	$city = $mgr->getDeviceCity($devsn);
	
}
$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp,
		API_CONSTANTS::API_PARAM_DEV_SN => $devsn+0,
		"dev_active_date"=>$date,
		"dev_active_city"=>$city);

echo json_encode($array);


?>