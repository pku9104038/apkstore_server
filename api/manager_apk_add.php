<?php  
/**
 * API: '../api/manager_apk_add.php'
 * 
 * @param $_POST['application_serial']
 * @param $_POST['apkfile']
 * @param $_POST['version_code']
 * @param $_POST['version_name']
 * @param $_POST['sdk_min']
 * 
 * @return JSON
 *         "api_resp":true/false
 */

//$supplier_serial = $_POST ['supplier_serial'];
if (isset($_REQUEST['supplier_serial'])) {
	$supplier_serial = $_REQUEST['supplier_serial'];
}
else{
	$supplier_serial = 0;
}
//$category_serial = 1;
$package = $_POST['package'];
$file_original = $_POST['file_original'];
$version_code = $_POST['version_code'];
$version_name = $_POST['version_name'];
$sdk_min = $_POST['sdk_min'];
if (isset($_REQUEST['customer_serial'])) {
	$customer_serial = $_REQUEST['customer_serial'];
}
else{
	$customer_serial = 0;
}
if (isset($_REQUEST['brand_serial'])) {
	$brand_serial = $_REQUEST['brand_serial'];
}
else{
	$brand_serial = 0;
}
if (isset($_REQUEST['model_serial'])) {
	$model_serial = $_REQUEST['model_serial'];
}
else{
	$model_serial = 0;
}
require_once '../utilities/class.applicationmanager.php';
$appMgr = new ApplicationManager();
$application_serial = $appMgr->getAppSerial($package);

require_once '../utilities/class.apkfilemanager.php';
$mgr = new ApkfileManager();

$apkfile_serial = $mgr->addApkfile($application_serial, $file_original, 
                $version_code, $version_name, $sdk_min, $supplier_serial,
				$customer_serial, $brand_serial, $model_serial); 

$err_msg = '新版本"'.$version_code.'"信息登记';
require_once 'api_constants.php';
if($apkfile_serial > 0 ){
    $err_msg .= '成功！';
    $array = Array(API_CONSTANTS::API_RESP => TRUE, 
            API_CONSTANTS::API_RESP_MSG => $err_msg);
}
else {
    $err_msg .= '失败！';
    $array = Array(API_CONSTANTS::API_RESP => FALSE, 
            API_CONSTANTS::API_RESP_MSG => $err_msg);
}
echo json_encode($array);
?>  