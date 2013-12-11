<?php
/**
 * API: '../api/manager_app_add.php'
 * 
 * @param $_POST['apkinfo_list']
 * 
 * @return JSONArray
 *         [{"index":index,"online_state":state}]
 */
require_once 'api_constants.php';
$apkinfo_list = $_POST[API_CONSTANTS::API_PARAM_APKINFO_LIST];
$json_apkinfo_list = json_decode($apkinfo_list);
$array_list = $json_apkinfo_list->apkinfo_list;
$customer_serial = $_REQUEST[API_CONSTANTS::API_PARAM_CUSTOMER_SERIAL]+0;
$brand_serial = $_REQUEST[API_CONSTANTS::API_PARAM_BRAND_SERIAL]+0;
$model_serial = $_REQUEST[API_CONSTANTS::API_PARAM_MODEL_SERIAL]+0;

require_once '../utilities/class.applicationmanager.php';
$appMgr = new ApplicationManager();
require_once '../utilities/class.apkfilemanager.php';
$apkMgr = new ApkfileManager();

foreach ($array_list as $apkinfo){
    $app_serial = $appMgr->isAppRegistered($apkinfo->package, $customer_serial);
    if($app_serial > 0){
        $apk_serial = $apkMgr->isApkRegistered($app_serial, $apkinfo->vercode, $customer_serial, $brand_serial, $model_serial,1);
        if ($apk_serial > 0){
            $apk_upload = $apkMgr->isApkUploaded($apk_serial);
            if ($apk_upload){
                $icon_upload = $appMgr->isIconUploaded($app_serial);
                if ($icon_upload){
                    $apkinfo->online_state = API_CONSTANTS::ONLINE_STATE_APK_UPLOADED;
                }
                else{
                    $apkinfo->online_state = API_CONSTANTS::ONLINE_STATE_ICON_UPLOAD;
                }
                
            }
            else{
               $apkinfo->online_state = API_CONSTANTS::ONLINE_STATE_APK_ONLINE;
            } 
        }
        else{
            $apkinfo->online_state = API_CONSTANTS::ONLINE_STATE_APP_ONLINE;
        }
    }
    else{
        $apkinfo->online_state = API_CONSTANTS::ONLINE_STATE_UNKNOWN;
    }
//    echo "state:".$apkinfo->online_state;
//    echo "package:".$apkinfo->package;
//    echo "vercode:".$apkinfo->vercode;
    unset($apkinfo->package);
    unset($apkinfo->vercode);
}
//$apkinfo_list = null;
//$apkinfo_list->apkinfo_list = json_encode($array_list);
if(count($array_list)>0){
    $resp = TRUE;
} 
else {
    $resp = FALSE;
}

echo json_encode(Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_RESP_MSG => json_encode($array_list)));

?>