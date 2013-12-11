<?php
 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "首页推荐更新"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "application_manager.php";
$link_name = '返回"应用产品基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

$err_captcha = "";
$err_puup = "";
$err_category = "";
$err_msg = "";
$upload_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $serial = $_POST ['serial'];
    $category_serial = $_POST ['category_serial'];
    //$application = $_POST['application'];
    $application = addslashes($_POST['application']);
    $puup_point = $_POST['puup_point'];
    
    $package = $_POST['package'];
    $icon = $_POST['icon'];
    $producer = $_POST['producer'];
    $description = $_POST['description'];
    $captcha = sha1 ( $_POST ['captcha'] );
   
    $output_form = FALSE;

    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }
    require_once '../utilities/class.utilities.php';
    if (!Utilities::checkNumber($puup_point)) {
    	$err_puup = "飞扬指数输入错误，请输入数字！";
    	$output_form = TRUE;
    }
    else{
    	$puup_point = $puup_point+0;
    }
    
    $update = false;
    if(!$output_form){
    	require_once '../utilities/class.applicationmanager.php';
	    $mgr = new ApplicationManager();
    	$update = $mgr->updateApplication($serial, $application, $puup_point, $category_serial,$producer, $description);//, $package, $iconfile); 
    }
    
    $err_msg = '应用产品"'.$application.'"信息修改';
    if($update){
        $err_msg .= '成功！';
    }
    else{
        $err_msg .= '失败！';
    }
    
    
}
else {
    $serial = $_GET ['serial'];
    $category_serial = $_GET ['category_serial'];
    $application = $_GET['application'];
    $puup_point = $_GET['puup_point'];
    $package = $_GET['package'];
    $icon = $_GET['icon'];
    $producer = $_GET['producer'];
    $description = $_GET['description'];
    $promotion = $_GET['promotion'];
    
    $output_form = TRUE;
}
if ($output_form) {
    require_once '../res/url_conf.php';
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
								<td><div align="right"><label for="icon">图标：</label></div></td>
								<td>
									<img alt="图标" src="<?php echo $path_apkicons.$icon;?>" width="48" height="48">
									<input id="icon" name="icon" type="hidden" value="<?php echo $icon; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="application">应用名称：</label></div>
								</td>
								<td >
									<input id="application" name="application" type="text" value="<?php echo $application; ?>" />
									<input id="serial" name="serial" type="hidden" value="<?php echo $serial; ?>" />
								</td>
							</tr>
							<tr>
								<td >
									<input id="promotion" name="promotion" type="radio" value=1 /><label>首页推荐</label>
									<input id="serial" name="serial" type="hidden" value="<?php echo $serial; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="puup_point">飞扬指数：</label></div>
									
								</td>
								<td >
									<input id="puup_point" name="puup_point" type="text" value="<?php echo $puup_point; ?>" />
									<label class="error"><?php echo $err_puup; ?></label>
								</td>
							</tr>
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
							<tr>
								<td>
									<div align="right"><label for="producer">出品人：</label></div>
								</td>
								<td >
									<input id="producer" name="producer" type="text" value="<?php echo $producer; ?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right"><label for="description">说明：</label></div></td>
								<td>
									<textarea id="description" name="description" rows="4" cols="40" ><?php echo $description; ?></textarea>
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
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
