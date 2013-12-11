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

$package = $_POST['package'];
$file_original = $_POST['file_original'];
$version_code = $_POST['version_code'];

$time = date('YmdHis');
$apkfile = $package.'_'.$time.'.apk';   

require_once '../api/api_constants.php';

$upload_path = API_CONSTANTS::PATH_UPLOAD;
$target_path = API_CONSTANTS::PATH_APK;

require_once '../utilities/class.utilities.php';
$apkfile_from = $upload_path//.$file_original;
        .Utilities::convertFileName($file_original);

$apkfile_to = $target_path.$apkfile;
        //.Utilities::convertFileName($apkfile);

require_once 'api_constants.php';
$err_msg = '版本"'.$version_code.'"文件拷贝与信息更新';
$array = Array(API_CONSTANTS::API_RESP => FALSE, 
            API_CONSTANTS::API_RESP_MSG => $err_msg.'失败！');

require_once '../utilities/class.applicationmanager.php';
$appMgr = new ApplicationManager();
$application_serial = $appMgr->getAppSerial($package);

require_once '../utilities/class.apkfilemanager.php';
$mgr = new ApkfileManager();
$apkfile_serial = $mgr->getSerialByAppVerCode($application_serial,$version_code);
if($apkfile_serial > 0){
    if(copy($apkfile_from, $apkfile_to)){
        $serial = $mgr->updateApkfileBySerial($apkfile_serial, $apkfile); 
        if($serial > 0 ){
            $err_msg .= '成功！';
            $array = Array(API_CONSTANTS::API_RESP => TRUE, 
                API_CONSTANTS::API_RESP_MSG => $err_msg);
        }
    }
}

echo json_encode($array);
?>  