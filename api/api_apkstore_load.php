<?php

require_once 'api_constants.php';
$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$brand = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_BRAND];
$model = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_MODEL];

$resp = FALSE;
if($brand=="Namo" || $brand=="NAMO"){
	if(  $model == "N880" 
//			|| $model == "N600"
			){
		$resp=TRUE;
	}
}

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp);

echo json_encode($array);

?>