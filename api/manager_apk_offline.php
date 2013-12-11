<?php

require_once 'api_constants.php';

if(isset($_POST[API_CONSTANTS::API_PARAM_APP_SERIAL])){
	$app_serial = $_POST[API_CONSTANTS::API_PARAM_APP_SERIAL]+0;
}
else{
	$app_serial = 0;
}
if(isset($_POST[API_CONSTANTS::API_PARAM_APP_LABEL])){
	$app_label = $_POST[API_CONSTANTS::API_PARAM_APP_LABEL];
}
else{
	$app_label = "";
}

if(isset($_POST['account'])){
	$account = $_POST['account'];
}
else{
	$account = "";
}
if(isset($_POST['password'])){
	$password = $_POST['password'];
}
else{
	$password = "";
}

$apiname = basename($_SERVER['SCRIPT_NAME'],".php");

$resp = TRUE;
$msg = "app_serial无效！";
if ($app_serial>0) {
	$msg = "帐号登录失败！";
	require_once '../utilities/class.accountmanager.php';
	$accountMgr = new AccountManager();
	$err_code = AccountManager::ERR_CODE;
	$checked = $accountMgr->checkLogin($account, $password);
	if ($checked["$err_code"] == AccountManager::ERR_NONE){
		$msg = "品牌无效！";
		$brand_serial = $accountMgr->getBrandSerialsByCustomer($account);
		if ($brand_serial[0]>0) {
			$msg = $app_label."下线失败！";
			require_once '../utilities/class.blacklistmanager.php';
			$mgr = new BlacklistManager();
			if ($mgr->addBlacklistRecord($brand_serial[0], $app_serial)) {
				$msg = $app_label."应用下线成功！";
			}
		}		
	}
	
}

$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp,
		API_CONSTANTS::API_RESP_MSG => "".$msg);

echo json_encode($array);

?>