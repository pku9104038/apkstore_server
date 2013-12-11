<?php


require_once '../utilities/class.applicationmanager.php';
require_once 'api_constants.php';
require_once '../utilities/class.brandmanager.php';
require_once '../utilities/class.modelmanager.php';
require_once '../utilities/class.devicemanager.php';

$devsn = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_SN]+0;
$imei = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_IMEI];

$mgr = new DeviceManager();
$dev_serial = $mgr->getDeviceSN($imei);


$package = $_REQUEST[API_CONSTANTS::API_PARAM_APP_PACKAGE];
$appname = $_REQUEST[API_CONSTANTS::API_PARAM_APP_NAME];
$brand = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_BRAND];
$appvercode = $_REQUEST[API_CONSTANTS::API_PARAM_APP_VEZRCODE]+0;


$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$resp = FALSE;
/*
if($appname != "斐讯云桌面" && $brand != "Phicomm"){
	$mgr = new ApplicationManager();
	
	$applications = $mgr->getApplicationByPackage($package);
	
	if ($applications) {
		$resp = TRUE;
	}
}
*/

$vercodemax = (round(($appvercode/1000000))+1)*1000000;
$mgr = new ApplicationManager();
$applications = $mgr->getApplicationByPackageVercodeMax($package,$vercodemax);
if ($applications) {
	$resp = TRUE;
}

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp,
		API_CONSTANTS::API_PARAM_DEV_SN => $devsn+0,
		API_CONSTANTS::API_RESP_ARRAY=>$applications);

echo json_encode($array);

//file_put_contents("../json/api_verson_sync.json",json_encode($array));

?>