<?php

require_once '../utilities/class.apkfilemanager.php';
require_once '../api/api_constants.php';
require_once '../proxy/class.databaseproxy.php';

$column_apkfile = DatabaseProxy::DB_COLUMN_APKFILE;
$column_apkfile_serial = DatabaseProxy::DB_COLUMN_APKFILE_SERIAL;

$mgr = new ApkfileManager();
$array = $mgr->getAllFiles();
foreach ($array as $apkfile){
	if (file_exists(API_CONSTANTS::PATH_APK.$apkfile["$column_apkfile"])){
		$sha1 = sha1_file(API_CONSTANTS::PATH_APK.$apkfile["$column_apkfile"]);
		$mgr->updateApkfileSha1($apkfile["$column_apkfile_serial"],$sha1);
	}
	
}
?>