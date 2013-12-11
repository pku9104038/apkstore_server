<?php
require_once '../utilities/class.applicationmanager.php';
require_once 'api_constants.php';

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$latest_stamp = $_REQUEST[API_CONSTANTS::API_PARAM_APP_UPDATE_STAMP];
$brand = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_BRAND];
$model = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_MODEL];

$resp = FALSE;

$mgr = new ApplicationManager();
$stamp = $mgr->getApplicationsLatestStamp();
$update_stamp = $mgr->getApplicationsLatestPuupStamp();

$puup_points = $mgr->getApplicationsPuup($latest_stamp);


if ($puup_points) {
	$resp = TRUE;
}

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp,
		API_CONSTANTS::API_RESP_STAMP => $stamp,
		API_CONSTANTS::API_PARAM_APP_PUUP_STAMP => $update_stamp,
		
		API_CONSTANTS::API_RESP_ARRAY=>$puup_points);

echo json_encode($array);

//file_put_contents("../json/api_applications_sync.json",json_encode($array));
?>