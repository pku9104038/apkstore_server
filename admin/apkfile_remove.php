<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "应用文件版本下线！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "apkfile_manager.php";
$link_name = '返回"应用文件版本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

$err_captcha = "";
$err_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $serial = $_POST ['serial'];
    $application= $_POST ['application'];
    $vercode = $_POST['vercode'];
    $supplier = $_POST ['supplier'];
    $icon = $_POST['icon'];
    $notes = $_POST['notes'];
    $captcha = sha1 ( $_POST ['captcha'] );
    
    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    
    require_once '../utilities/class.apkfilemanager.php';
    $mgr = new ApkfileManager();
   
    if (!$output_form){
        $err_msg = $application.'版本"'.$vercode.'"下线';
        //if($mgr->removeApplication($serial)){
        if($mgr->onoffApkfile($serial, 0)){
            $err_msg .= '成功！';
        }
        else{
            $err_msg .= '失败！';
            $output_form = TRUE;
        }
    }
    
}
else if(isset($_POST['cancel'])){
    header("Location: apkfile_manager.php");
}
else {
    $serial = $_GET ['serial'];
    $vercode = $_GET ['vercode'];
    $application = $_GET ['application'];
    $icon = $_GET['icon'];
    $supplier = $_GET['supplier'];
    $notes = $_GET['notes'];
    $captcha = "";
    
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
									<div align="right"><label for="vercode">版本码：</label></div>
								</td>
								<td >
									<label ><?php echo $vercode; ?></label>
									<input id="vercode" name="vercode" type="hidden" value="<?php echo $vercode; ?>" />
								</td>
							</tr>
							
							<tr>
								<td>
									<div align="right"><label for="supplier">供应商：</label></div>
								</td>
								<td >
									<label ><?php echo $supplier; ?></label>
									<input id="supplier" name="supplier" type="hidden" value="<?php echo $supplier; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="notes">备注：</label></div>
								</td>
								<td >
									<?php echo $notes; ?>
									<input id="notes" name="notes" type="hidden" value="<?php echo $notes; ?>" />
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
