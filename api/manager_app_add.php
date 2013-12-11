<?php  
/**
 * API: '../api/manager_app_add.php'
 * 
 * @param $_POST['application']
 * @param $_POST['package']
 * @param $_POST['icon']
 * 
 * @return JSON
 *         "api_resp":true/false
 */
require_once '../utilities/class.log.php';
$category_serial = $_POST ['category_serial'];
//$category_serial = 1;
$application = addslashes($_POST['application']);
$package = $_POST['package'];
$icon = $_POST['icon'];

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
$mgr = new ApplicationManager();

require_once '../utilities/class.utilities.php';   
require_once 'api_constants.php';

$target_path  = API_CONSTANTS::PATH_ICON;//接收文件目录  
$icon_save = $target_path.Utilities::convertFileName($icon);

$application_serial = $mgr->addApplication($application, $category_serial, $package, $icon,
		$customer_serial,$brand_serial,$model_serial); 
$err_msg = '新应用产品"'.$application.'"注册';
require_once 'api_constants.php';
if($application_serial > 0 ){
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