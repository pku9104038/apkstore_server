<?php
 
require_once '../admin/check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once '../admin/role_check.php';

$page_title = "应用产品推荐更新！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';

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
    $group_serial = $_POST ['group_serial'];
    $category_serial = $_POST ['category_serial'];
    //$application = $_POST['application'];
    $application = addslashes($_POST['application']);
    $promotion = $_POST['promotion'];
    
    $package = $_POST['package'];
    $icon = $_POST['icon'];
    $producer = $_POST['producer'];
    $description = $_POST['description'];
    $captcha = sha1 ( $_POST ['captcha'] );

    $link = "application_manager_group.php".'?group_serial='.$group_serial;
    $link_name = '返回"分组应用产品管理"';
    require_once '../res/righttop_link.php';    
    $output_form = FALSE;

    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }
    
    $update = false;
    if(!$output_form){
    	require_once '../utilities/class.applicationmanager.php';
	    $mgr = new ApplicationManager();
    	$update = $mgr->updateApplicationPromotion($serial, $promotion);
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
    $promotion = $_GET['promotion'];
    $group_serial = $_GET['group_serial'];
    $package = $_GET['package'];
    $icon = $_GET['icon'];
    $producer = $_GET['producer'];
    $description = $_GET['description'];
    
    $link = "application_manager_group.php".'?group_serial='.$group_serial;
    $link_name = '返回"分组应用产品管理"';
    require_once '../res/righttop_link.php';
    $output_form = FALSE;
    
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
									<?php echo $application; ?>
									<input id="serial" name="serial" type="hidden" value="<?php echo $serial; ?>" />
									<input id="group_serial" name="group_serial" type="hidden" value="<?php echo $group_serial; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="promotion">首页推荐：</label></div>
									
								</td>
								<td >
									<select id="promotion" name="promotion" >
										<?php 
										if ($promotion) {
											echo '<option value="1" selected="selected" >是</option>';
											echo '<option value="0"  >否</option>';
										}
										else{
											echo '<option value="1" >是</option>';
											echo '<option value="0" selected="selected" >否</option>';
										}
										?>
										
									</select>
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
