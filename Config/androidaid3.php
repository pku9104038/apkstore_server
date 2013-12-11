<?php
$array[0] = Array("download_root"=>"http://www.pu-up.com/ApkStore/download");
$array[1] = Array("download_root"=>"http://www.namo.com.cn:8088/ApkStore/download");
$array[2] = Array("download_root"=>"http://www.q-shuttle.com/ApkStore/download"); 
/*
echo json_encode(Array("app_name"=>"AndroidAid3",
		"conf_date"=>"20130315",
		"api_root"=>"http://www.pu-up.com/ApkStore/api",
		"download_conf"=>$array));
		*/
require_once '/utilities/class.groupmanager.php';
require_once '/api/api_constants.php';

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");

$array = Array(API_CONSTANTS::API=>$apiname, API_CONSTANTS::API_RESP => FALSE,API_CONSTANTS::API_RESP_ARRAY=>$array);

echo json_encode($array);
?>