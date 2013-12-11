<?php
require_once '../utilities/class.downloadmanager.php';
require_once 'api_constants.php';

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$resp = FALSE;

$devsn = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_SN]+0;


if($devsn > 0){
	$package = $_REQUEST[API_CONSTANTS::API_PARAM_REPORT_PACKAGE];
	$vercode = $_REQUEST[API_CONSTANTS::API_PARAM_REPORT_VERCODE]+0;
	$action = $_REQUEST[API_CONSTANTS::API_PARAM_REPORT_ACTION]+0;
	$stamp = $_REQUEST[API_CONSTANTS::API_PARAM_REPORT_STAMP];
	$mgr = new DownloadManager();
	if($mgr->isNewRecord($devsn, $package, $vercode, $action, $stamp)){
		if($mgr->addRecord($devsn, $package, $vercode, $action, $stamp)){
			$resp = TRUE;
		}
	}
	else{
		$resp = TRUE;
	}
}
$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp);

echo json_encode($array);

?>