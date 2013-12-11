<?php

require_once '../utilities/class.groupmanager.php';
require_once 'api_constants.php';

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$resp = FALSE;
$brand = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_BRAND];

require_once '../utilities/class.brandmanager.php';
$brandMgr = new BrandManager();
$brand_serial = $brandMgr->getBrandSerial($brand);
$customer_serial = $brandMgr->getBrandCustomerSerial($brand_serial);

$mgr = new GroupManager();
$stamp = $mgr->getGroupsLatestStamp();
$groups = $mgr->getGroups($customer_serial);

if ($groups) {
	$resp = TRUE;
}

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp,
		API_CONSTANTS::API_RESP_STAMP => $stamp,
		API_CONSTANTS::API_PARAM_DEV_BRAND => $brand,
		API_CONSTANTS::API_RESP_ARRAY=>$groups);

echo json_encode($array);

file_put_contents("../json/api_group_sync.json",json_encode($array));

?>