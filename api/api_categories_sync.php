<?php
require_once '../utilities/class.groupmanager.php';
require_once '../utilities/class.categorymanager.php';
require_once 'api_constants.php';

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$resp = FALSE;
$groupMgr = new GroupManager();
$groups = $groupMgr->getGroupsSerialArray();

$mgr = new CategoryManager();
$stamp = $mgr->getCategoriesLatestStamp();

$group_categories = $mgr->getGroupsCategories($groups);

if ($group_categories) {
	$resp = TRUE;
}

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp,
		API_CONSTANTS::API_RESP_STAMP => $stamp,
		API_CONSTANTS::API_RESP_ARRAY=>$group_categories);

echo json_encode($array);


?>