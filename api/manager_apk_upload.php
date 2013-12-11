<?php

ini_set('max_execution_time', '0');

require_once '../utilities/class.log.php';
require_once '../utilities/class.utilities.php';    
require_once 'api_constants.php'; 

$target_path  = API_CONSTANTS::PATH_APK;//接收文件目录  
$upload_path = API_CONSTANTS::PATH_UPLOAD;
//$androidaid_path = '../../androidaid/download/Online/';

$package = $_POST['package'];
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

$file_original = basename( $_FILES['uploadedfile']['name']);
$file_original = $_POST['original_file'];

$version_code = $_POST['version_code']+0;
$sha_from = $_POST['sha_from'];

$apkfile_upload = $_FILES['uploadedfile']['error'];
$upload_msg = Utilities::checkUploadError($apkfile_upload);
$err_msg = $upload_msg;

$array = Array(API_CONSTANTS::API_RESP => FALSE,API_CONSTANTS::API_RESP_MSG => $apkfile_upload);

require_once '../utilities/class.applicationmanager.php';
$appMgr = new ApplicationManager();
$app_serial = $appMgr->isAppRegistered($package);

require_once '../utilities/class.apkfilemanager.php';
$mgr = new ApkfileManager();
//$apk_package = $mgr->getApkPackageByOriginal($file_original);
$apk_serial = $mgr->isApkRegistered($app_serial, $version_code, $customer_serial, $brand_serial, $model_serial);

$file_original = $mgr->getApkFileOriginal($apk_serial);
$time = date('YmdHis');
//$apkfile = $apk_package.'_'.$time.'.apk';   
$apkfile = $package.'_'.$version_code.'_'.$time.'.apk';   

$apkfile_save = $target_path
        .Utilities::convertFileName($apkfile);

//$androidaid_save = $androidaid_path.Utilities::convertFileName($apkfile);

$latest_save = $target_path.Utilities::convertFileName("ApkStore.apk");
        
$originalfile_save = $upload_path
        .Utilities::convertFileName($file_original);
        

$resp = FALSE;
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $apkfile_save/*$originalfile_save*/)) { 
//    if(copy($originalfile_save, $apkfile_save)){ 
//        if ($mgr->updateApkfile($file_original, $apkfile)){

	$sha = sha1_file($apkfile_save,false);
	//file_put_contents("../json/manager_apk_upload.json",json_encode(Array("file_upload"=>$_FILES['uploadedfile']['name'],"sha"=>$sha, "sha_from"=>$sha_from)));
	if($sha==$sha_from){
		
		$samefile = $mgr->isApkSame($sha);
		if ($samefile) {
			unlink($apkfile_save);
			$apkfile = $samefile;
			
		}
		if ($mgr->updateApkfileInfo($app_serial,$version_code,$file_original, $apkfile,$sha,$customer_serial,$brand_serial,$model_serial)){
            $err_msg .= " 新版本文件信息更新成功！";
            $resp = TRUE;
            
//          copy($apkfile_save, $androidaid_save);
			if($package == "com.android.aid"){
				unlink($latest_save);
	
				copy($apkfile_save, $latest_save);
			}
            
        }
        else {
            $err_msg .= " 新版本文件信息更新失败！";
        }
	}
    else{
    	$err_msg .= " 文件sha1校验失败！";
    }
        
//    }
/*
    else{
        Log::i("error:".$upload_msg.$file_original);
        $err_msg .= " 文件拷贝失败！";
        $array = Array(API_CONSTANTS::API_RESP => FALSE,API_CONSTANTS::API_RESP_MSG => $err_msg);
        Log::i("err_message:",$err_msg);
    }
 */   
    unlink($originalfile_save);
}
else{
    $err_msg .= " 文件移动失败！";
}
$array = Array(API_CONSTANTS::API_RESP => $resp,API_CONSTANTS::API_RESP_MSG => $err_msg);
echo json_encode($array);
    
?>