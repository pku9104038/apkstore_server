<?php

require_once '../utilities/class.applicationmanager.php';
require_once 'api_constants.php';

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$latest_stamp = $_REQUEST[API_CONSTANTS::API_PARAM_APP_UPDATE_STAMP];
if (isset($_REQUEST[API_CONSTANTS::API_PARAM_APP_UPDATE_STAMP])) {
	$latest_stamp = $_REQUEST[API_CONSTANTS::API_PARAM_APP_UPDATE_STAMP];
}
else{
	$latest_stamp = "2013-01-01 00:00:00";
}

$brand = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_BRAND];
$model = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_MODEL];
$sdk_level = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_SDK]+0;

require_once '../utilities/class.brandmanager.php';
$brandMgr = new BrandManager();
$brand_serial = $brandMgr->getBrandSerial($brand);
$customer_serial = $brandMgr->getBrandCustomerSerial($brand_serial);

require_once '../utilities/class.modelmanager.php';
$modelMgr = new ModelManager();
$model_serial = $modelMgr->getModelSerial($model, $brand_serial);

$resp = FALSE;

$mgr = new ApplicationManager();
$stamp = $mgr->getApplicationsLatestStamp();

$update_stamp = $mgr->getApplicationsLatestUpdateStamp();

$applications = $mgr->getApplications($customer_serial,$latest_stamp, $brand_serial,$model_serial,$sdk_level);

if ($applications) {
	$resp = TRUE;
}

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp,
		API_CONSTANTS::API_RESP_STAMP => $stamp,
		API_CONSTANTS::API_PARAM_APP_UPDATE_STAMP => $update_stamp,
		API_CONSTANTS::API_RESP_ARRAY=>$applications);

echo json_encode($array);

file_put_contents("../json/api_applications_sync.json",json_encode($array));
?>