<?php
/**
 * API: '../api/manager_login.php'
 * 
 * @param $_POST['account']
 * @param $_POST['password']
 * 
 * @return JSON
 *         "api_resp":true/false
 */


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
require_once '../utilities/class.accountmanager.php';
require_once '../api/api_constants.php';
require_once '../utilities/class.suppliermanager.php';

$resp = FALSE;
$customer_serial = 0;
$supply_serial = 1;
$mgr = new AccountManager();
$err_code = AccountManager::ERR_CODE;
$checked = $mgr->checkLogin($account, $password);
if ($checked["$err_code"] == AccountManager::ERR_NONE){
    $resp = TRUE;
    $customer_serial = $mgr->getAccountCustomerSerial($account);
	$role_id = $mgr->getRoleID($account);
	$supplierMgr = new SupplierManager();
	$supply_serial = $supplierMgr->getSupplierByCustomer($customer_serial);
	
}
    
$array = Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_PARAM_CUSTOMER_SERIAL => $customer_serial,
		API_CONSTANTS::API_PARAM_SUPPLIER_SERIAL => $supply_serial,
		API_CONSTANTS::API_PARAM_ROLE_ID => $role_id
		);

echo json_encode($array);
?>
