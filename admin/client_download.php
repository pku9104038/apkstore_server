<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员

);
require_once 'role_check.php';

$page_title = "客户端下载"; 
require_once '../res/header.php';
require_once '../res/navigator.php';

require_once '../res/righttop_link.php';

?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_serial = DatabaseProxy::DB_COLUMN_APKFILE_SERIAL;
$column_apkfile = DatabaseProxy::DB_COLUMN_APKFILE;
$column_version_code = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_CODE;
$column_version_name = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_NAME;
$column_sdk_min = DatabaseProxy::DB_COLUMN_APKFILE_SDK_MIN;
$column_notes = DatabaseProxy::DB_COLUMN_APKFILE_NOTES;
$column_register_date = DatabaseProxy::DB_COLUMN_APKFILE_REGISTER_DATE;
$column_update_time = DatabaseProxy::DB_COLUMN_APKFILE_UPDATE_TIME;
$column_application = DatabaseProxy::DB_COLUMN_APPLICATION;
$column_application_serial = DatabaseProxy::DB_COLUMN_APPLICATION_SERIAL;
$column_icon = DatabaseProxy::DB_COLUMN_APPLICATION_IOCN;
$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
$column_category_serial = DatabaseProxy::DB_COLUMN_CATEGORY_SERIAL;
$column_supplier_serial = DatabaseProxy::DB_COLUMN_SUPPLIER_SERIAL;
$column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;


?>


					<div align="center">
						<table width="100%" border="3">
							<tr>
								<td width="48">
									<div class="column_name">图标</div>
								</td>
								<td>
									<div class="column_name">应用名称</div>
								</td>
								<td>
									<div class="column_name">版本码</div>
								</td>
								<td>
									<div class="column_name">版本名称</div>
								</td>
								<td>
									<div class="column_name">SDK版本</div>
								</td>
								<td>
									<div class="column_name">应用类型</div>
								</td>
								<td>
									<div class="column_name">apk文件</div>
								</td>
								<td>
									<div class="column_name">更新时间</div>
								</td>
							</tr>

<?php
require_once '../res/url_conf.php';
require_once '../utilities/class.apkfilemanager.php';
$mgr = new ApkFileManager();

//$packageArray = Array( "com.android.aid","cn.com.namo.apkstore.manager");
$packageArray[0] = "com.android.aid";
$packageArray[1] = "cn.com.namo.apkstore.manager";
$vercodeMaxArray[0] = 11000000;
$vercodeMaxArray[1] = 11000000;
$array = $mgr->fetchApkFilesByPackageVercodeMaxArray($packageArray,$vercodeMaxArray);

//$array = $mgr->fetchApkFilesByPackageArray($packageArray);
//$packageArray = "cn.com.namo.apkstore.manager";
//$array = $mgr->fetchApkFilesByPackageArray($packageArray);
foreach ($array as $row){
?>

							<tr>
								<td width="48">
									<img src="<?php echo $path_apkicons.$row["$column_icon"];?>" width="48" height="48">
								</td>
								<td >
									<div class="column_value"><?php echo $row["$column_application"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_version_code"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_version_name"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_sdk_min"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_category"];?></div>
								</td>
								<td>
<?php 				
					require_once '../api/api_constants.php';
					$path_apk = API_CONSTANTS::PATH_APK;
                    echo  '<div class="column_valuer"><a href="'
                            .$path_apk
                            .$row["$column_apkfile"]
                            .'">'
                            .$row["$column_apkfile"]
                            .'</a></div>';
            
?>								
					            
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_update_time"];?></div>
								</td>
							</tr>
    
<?php     
}

?>
						</table>

<?php 
require_once '../res/footer.php';
?>
