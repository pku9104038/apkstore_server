<?php
require_once '../utilities/class.cellonlinemanager.php';
require_once 'api_constants.php';

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$resp = FALSE;

$devsn = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_SN]+0;

if($devsn > 0){
	$date = $_REQUEST[API_CONSTANTS::API_PARAM_REPORT_DATE];
	$counts = $_REQUEST[API_CONSTANTS::API_PARAM_REPORT_COUNT]+0;
	$mgr = new CellOnlineManager();
	if($mgr->isNewRecord($devsn, $date)){
		if($mgr->addRecord($devsn, $date, $counts)){
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