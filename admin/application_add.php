<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "新应用产品上架！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "application_manager.php";
$link_name = '返回"应用产品基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php
require_once '../utilities/class.utilities.php';
require_once '../utilities/class.log.php';

    $category = "";
    $application = "";
    $package = "";
    $notes = "";
    $captcha = "";

    $err_msg = "";
    $err_captcha = "";
    $err_category = "";
    $upload_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $serial = $_POST ['serial'];
    $category = $_POST ['category'];
    $category_serial = $_POST ['category_serial'];
    $group_serial = $_POST ['group_serial'];
    $application = addslashes($_POST['application']);
    $package = $_POST['package'];
    $notes = $_POST['notes'];
    $captcha = sha1 ( $_POST ['captcha'] );
   
    $output_form = FALSE;
    
    require_once '../api/api_constants.php';
    
/*    
    $apkfile_upload = $_FILES['apkfile']['error'];
    $apkfile_path = $_FILES['apkfile']['tmp_name'];
    $apkfile_original = $_FILES['apkfile']['name'];
    $apkfile_type = $_FILES['apkfile']['type'];
    $apkfile_size = $_FILES['apkfile']['size'];
    
    require_once '../utilities/class.utilities.php';
    $apkfile_name = Utilities::convertFileName($_FILES['apkfile']['name']);
    $apk_save = API_CONSTANTS::PATH_UPLOAD.$apkfile_name;
    $upload_msg = Utilities::checkUploadError($apkfile_upload);
    if ($upload_msg>0){
        $output_form = TRUE;
    }
    else{
        if(copy($_FILES['apkfile']['tmp_name'],$apk_save)){
            $apkfile = $apk_save;
            $upload_msg .= " ".$apkfile_type." 文件:".$apkfile_original." 大小:".$apkfile_size;
        }
        else{
            $upload_msg .= "文件拷贝出错！";
            $output_form = TRUE;
        }
    }
*/    
    $iconfile_upload = $_FILES['iconfile']['error'];
    $iconfile_path = $_FILES['iconfile']['tmp_name'];
    $iconfile_original = $_FILES['iconfile']['name'];
    $iconfile_type = $_FILES['iconfile']['type'];
    $iconfile_size = $_FILES['iconfile']['size'];
    require_once '../utilities/class.utilities.php';
    require_once '../api/api_constants.php';
    $iconfile = API_CONSTANTS::PATH_ICON.$_FILES['iconfile']['name'];
    $icon_save = API_CONSTANTS::PATH_ICON.Utilities::convertFileName($_FILES['iconfile']['name']);
    $upload_msg = Utilities::checkUploadError($iconfile_upload);
    if ($upload_msg>0){
        $output_form = TRUE;
    }
    else{
        if(copy($_FILES['iconfile']['tmp_name'],$icon_save)){
            $upload_msg .= " ".$iconfile_type." 文件:".$iconfile_original." 大小:".$iconfile_size;
        }
        else{
            $upload_msg .= "文件拷贝出错！";
            $output_form = TRUE;
        }
    }
    
    //check capthca
    //session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
//        $output_form = TRUE;
    }

    require_once '../utilities/class.applicationmanager.php';
    $mgr = new ApplicationManager();
    $application_serial = $mgr->addApplication($application, $category_serial, $package, $iconfile); 
    $err_msg = '新应用产品"'.$application;
        
    if($application_serial > 0 ){
        $err_msg .= '"添加成功！';
/*        
        require_once '../utilities/class.apkfilemanager.php';
        $apkfileMgr = new ApkfileyManager();
        $apkfile_serial = $apkfileMgr->addApkfile($apkfile,$application_serial);
        if ($apkfile_serial == 0){
            $output_form = TRUE;
        }
        else{
            $err_msg .= 'APK文件'.$apkfile_name.'添加成功！';
        }
*/        
    }
    else{
        $err_msg .= '"添加失败！';
    }
    
    
}
else {
    $output_form = TRUE;
}
if ($output_form) {
    require_once '../proxy/class.databaseproxy.php';
    $column_group_serial = DatabaseProxy::DB_COLUMN_GROUP_SERIAL;
    $column_group = DatabaseProxy::DB_COLUMN_GROUP;
    $column_category_serial = DatabaseProxy::DB_COLUMN_CATEGORY_SERIAL;
    $column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
?>

				<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
					<div align="center">
						<p class="error"><?php echo $err_msg?></p>
						<p class="error"><?php echo $upload_msg?></p>
						<table>
							<tr>
								<td>
									<div align="right"><label for="category_serial">应用类型：</label></div>
								</td>
								<td >
									<select id="category_serial" name="category_serial">
<?php 

    require_once '../utilities/class.categorymanager.php';
    $categoryMgr = new CategoryManager();
    $array_categories = $categoryMgr->fetchCategories();
    for($i=0; $i<count($array_categories); $i++){
        $strCategorySelect = '<option value="'.$array_categories[$i]["$column_category_serial"].'"';
        if(!empty($category_serial) && $category_serial==$array_categories[$i]["$column_category_serial"]){
            $strCategorySelect .= 'selected = "selected"';
            $category = $array_categories[$i]["$column_category"];
            $group_serial = $array_categories[$i]["$column_group_serial"];
        }
        $strCategorySelect .= '>'.$array_categories[$i]["$column_category"].'</option>';
        echo $strCategorySelect;
    }

?>
									</select>
								</td>
							</tr>
							<!-- 
							<tr>
								<td>
									<div align="right"><label for="group">应用分组：</label></div>
								</td>
								<td >
								 -->
<?php 
/*
    require_once '../utilities/class.groupmanager.php';
    $groupMgr = new GroupManager();
    $array_groups = $groupMgr->getGroups();
    for($i=0; $i<count($array_groups); $i++){
        $echostring = '<option value="'.$array_groups[$i]["$column_group_serial"].'"';
        if(!empty($group_serial) && $group_serial==$array_groups[$i]["$column_group_serial"]){
            $echostring .= 'selected = "selected"';
            $group = $array_groups[$i]["$column_group"];
        }
        $echostring .= '>'.$array_groups[$i]["$column_group"].'</option>';
//        echo $echostring;
    }
*/
?>
<!-- 
									<input id="group" name="group" type="hidden" value="<?php //echo $group; ?>" />
									<?php //echo $group;?>
								</td>
							</tr>
							 -->
							<tr>
								<td>
									<div align="right"><label for="application">应用名称：</label></div>
								</td>
								<td >
									<input id="application" name="application" type="text" value="<?php echo $application; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="package">应用包名：</label></div>
								</td>
								<td >
									<input id="package" name="package" type="text" value="<?php echo $package; ?>" />
								</td>
							</tr>
							<!-- 
							<tr>
								<td><div align="right"><label for="apkfile">APK文件：</label></div></td>
								<td>
									<input type="file" id="apkfile" name="apkfile" />
								</td>
							</tr>
							 -->
							<tr>
								<td><div align="right"><label for="iconfile">图标文件：</label></div></td>
								<td>
									<input type="file" id="iconfile" name="iconfile" />
								</td>
							</tr>
							<tr>
								<td><div align="right"><label for="captcha">验证码：</label></div></td>
								<td>
									<input id="captcha" name="captcha" type="text" value="" />
									<img align="top" src="../res/captcha.php" alt="验证码" />
									<label class="error"><?php echo $err_captcha; ?></label>
								</td>
							</tr>
							<tr>
								<td><br /></td>
							</tr>
							<tr>
								<td></td>
								<td><div align="left"><input type="submit" name="submit" value="确认提交" /></div></td>
							</tr>
						</table>
					</div>
				</form>

<?php
} 
else {
?>
				<div align="center">
					<p class="error"><?php echo $err_msg;?></p>
					<p><a href="application_add.php">添加下一个？</a></p>
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
