<?php


require_once 'check_login.php';
$roles_required = Array(
		2,    //管理员
		4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "应用产品信息更新！";
require_once '../res/header.php';
require_once '../res/navigator.php';
//$link = "application_manager.php";
//$link_name = '返回"应用产品基本信息管理"';
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
    $category = $_POST ['category'];
    //$application = $_POST['application'];
    $application = addslashes($_POST['application']);
    
    $package = $_POST['package'];
    $icon = $_POST['icon'];
    $introduce = $_POST['introduce'];
    $captcha = sha1 ( $_POST ['captcha'] );
   
    $output_form = FALSE;

    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }
    
    $update = false;
    if(!$output_form){
    	require_once '../utilities/class.applicationmanager.php';
	    $mgr = new ApplicationManager();
    	$update = $mgr->updateApplicationIntroduce($serial+0,  $introduce);//, $package, $iconfile); 
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
    $application = $_GET['application'];
    $category = $_GET['category'];
    $package = $_GET['package'];
    $icon = $_GET['icon'];
    
    require_once '../utilities/class.applicationmanager.php';
    $mgr = new ApplicationManager();
    
    $introduce = $mgr->getApplicationIntroduce($serial+0);//, $package, $iconfile);
    
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
									<input id="serial" name="serial" type="hidden" value="<?php echo $serial; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="application">应用名称：</label></div>
								</td>
								<td >
									<div align="left"><label for="application_name"><?php echo $application; ?></label></div>
									<input id="application" name="application" type="hidden" value="<?php echo $application; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="category">应用类型：</label></div>
								</td>
								<td >
									<div align="right"><label for="category_name"><?php echo $category; ?></label></div>
									<input id="category" name="category" type="hidden" value="<?php echo $category; ?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right"><label for="description">说明：</label></div></td>
								<td>
									<textarea id="introduce" name="introduce" rows="10" cols="100" ><?php echo $introduce; ?></textarea>
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


