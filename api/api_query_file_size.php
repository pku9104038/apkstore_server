<?php

require_once 'api_constants.php';

$app_filename = $_REQUEST[API_CONSTANTS::API_PARAM_APK_FILE_NAME];

$target_path  = API_CONSTANTS::PATH_APK.$app_filename;//接收文件目录
$filesize = filesize($target_path);

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
if ($filesize) {
	$resp = TRUE;
}
else{
	$resp = FALSE;
}

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp,
		API_CONSTANTS::API_RESP_MSG => "".$filesize);

echo json_encode($array);

?>