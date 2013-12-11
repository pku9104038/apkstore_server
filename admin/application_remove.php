<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "应用产品下线！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "application_manager.php";
$link_name = '返回"应用产品基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

$err_captcha = "";
$err_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $serial = $_POST ['serial'];
    $application= $_POST ['application'];
    $category = $_POST ['category'];
    $icon = $_POST['icon'];
    $package = $_POST['package'];
    $captcha = sha1 ( $_POST ['captcha'] );
    
    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    
    require_once '../utilities/class.applicationmanager.php';
    $mgr = new ApplicationManager();
   
    if (!$output_form){
        $err_msg = '应用产品"'.$application.'"下线';
        //if($mgr->removeApplication($serial)){
        if($mgr->onoffApplication($serial, 0)){
            $err_msg .= '成功！';
        }
        else{
            $err_msg .= '失败！';
            $output_form = TRUE;
        }
    }
    
}
else if(isset($_POST['cancel'])){
    header("Location: application_manager.php");
}
else {
    $serial = $_GET ['serial'];
    $category = $_GET ['category'];
    $application = $_GET ['application'];
    $icon = $_GET['icon'];
    $package = $_GET['package'];
    $captcha = "";
    
    $err_captcha = "";
    $output_form = TRUE;
}
if ($output_form) {
require_once '../res/url_conf.php';
?>

				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<div align="center">
						<p class="error"><?php echo $err_msg?></p>
						<table>
							<tr>
								<td>
								</td>
								<td >
									<img src="<?php echo $path_apkicons.$icon; ?>" width="48" height="48"/>
									<input id="icon" name="icon" type="hidden" value="<?php echo $icon;?>" />
								</td>
							<tr>
								<td>
									<div align="right"><label for="application">应用产品：</label></div>
								</td>
								<td >
									<label ><?php echo $application; ?></label>
									<input id="application" name="application" type="hidden" value="<?php echo $application; ?>" />
									<input id="serial" name="serial" type="hidden" value="<?php echo $serial; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="category">应用类型：</label></div>
								</td>
								<td >
									<label ><?php echo $category; ?></label>
									<input id="category" name="category" type="hidden" value="<?php echo $category; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="package">应用包名：</label></div>
								</td>
								<td >
									<label ><?php echo $package; ?></label>
									<input id="package" name="package" type="hidden" value="<?php echo $package; ?>" />
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
								<td><div align="right"><input type="submit" name="submit" value="确认" /></div></td>
								<td><div align="center"><input type="submit" name="cancel" value="取消" /></div></td>
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
