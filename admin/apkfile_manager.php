<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "应用文件版本信息管理！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "apkfile_manager_offline.php";
$link_name = "已下线版本管理";
require_once '../res/righttop_link.php';

$page_fetch_limit = 20;
$page_index_max = 10;
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



$session_name = 'apkfile';
$default_sort = $column_application;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';

?>


					<div align="center">
						<table width="100%" border="3">
							<tr>
								<td width="48">
									<div class="column_name">图标</div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_application&order=$reorder"; ?>">应用名称</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_version_code&order=$reorder"; ?>">版本码</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_version_name&order=$reorder"; ?>">版本名称</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_sdk_min&order=$reorder"; ?>">SDK版本</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_category&order=$reorder"; ?>">应用类型</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_supplier&order=$reorder"; ?>">供应商</a></div>
								</td>
								<td width="30%">
									<div class="column_name">apk文件</div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_update_time&order=$reorder"; ?>">更新时间</a></div>
								</td>
								<td></td>
								<td></td>
							</tr>

<?php
require_once '../res/url_conf.php';
require_once '../utilities/class.apkfilemanager.php';
$mgr = new ApkFileManager();

$limit = $page_fetch_limit;
$online = 1;
$total = $mgr->getTotal($online);
$num_pages = ceil($total/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}
$array = $mgr->fetchApkFiles($sort, $cur_page, $limit, $order,$online);
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
									<div class="column_value"><?php echo $row["$column_supplier"];?></div>
								</td>
								<td width="30%">
									<div class="column_value"><?php echo $row["$column_apkfile"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_update_time"];?></div>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="apkfile_edit.php'
            .'?serial='.$row["$column_serial"]
            .'&application='.$row["$column_application"]
            .'&vercode='.$row["$column_version_code"]
            .'&supplier_serial='.$row["$column_supplier_serial"]
            .'&notes='.$row["$column_notes"]
            .'&icon='.$row["$column_icon"]
            .'">修改</a></div>';
?>
								</td>
								<td>
<?php 
    echo  '<div class="column_value_center"><a href="apkfile_remove.php'
            .'?serial='.$row["$column_serial"]
            .'&application='.$row["$column_application"]
            .'&vercode='.$row["$column_version_code"]
            .'&supplier='.$row["$column_supplier"]
            .'&notes='.$row["$column_notes"]
            .'&icon='.$row["$column_icon"]
            .'">版本下线</a></div>';
?>
								</td>
							</tr>
    
<?php     
}
require_once '../utilities/class.utilities.php';
$page_links = Utilities::generate_page_links($_SERVER['PHP_SELF'], $sort, $order, $cur_page, $num_pages, $page_index_max);

?>
						</table>
					</div>
					<div align="right">
						<?php echo $page_links;?>
					</div>

<?php 
require_once '../res/footer.php';
?>
