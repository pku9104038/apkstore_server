<?php
require_once '../utilities/class.applicationmanager.php';
require_once 'api_constants.php';

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$resp = TRUE;

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp);

echo json_encode($array);

?>