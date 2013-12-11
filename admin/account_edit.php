<?php 
require_once 'check_login.php';
$roles_required = Array(
    2    //管理员

);
require_once 'role_check.php';

$page_title = "添加新帐号！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "account_manager.php";
$link_name = '返回"帐号管理"';
require_once '../res/righttop_link.php';
?>

<?php

$err_captcha = "";
$err_email = "";
$err_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $account = $_POST ['account'];
    $role_id = $_POST['role_id'];
    $email = $_POST ['email'];
    $captcha = sha1 ( $_POST ['captcha'] );

    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    require_once '../utilities/class.utilities.php';
    if(!Utilities::checkEmail($email)){
        $err_email = "无效电子邮箱！";
        $output_form = TRUE;
    }
    
    require_once '../utilities/class.accountmanager.php';
    $mgr = new AccountManager();
    if(!$mgr->checkAccountNewEmail($account, $email)){
        $err_email = "电子邮箱重复！";
        $output_form = TRUE;
    }
    if (!$output_form){
        if($mgr->updateAccount($account, $role_id ,$email)){
            $err_msg = '帐号"'.$account.'"信息修改成功！';
        }
        else{
            $err_msg = '帐号"'.$account.'"信息修改失败！';
            $output_form = TRUE;
        }
    }
    
}
else {
    $account = $_GET['account'];
    $email = $_GET['email'];
    $role_id = $_GET['role_id'];
    
    $output_form = TRUE;
}
if ($output_form) {
?>

				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<div align="center">
						<table>
							<tr>
								<td></td>
								<td><p class="error"><?php echo $err_msg?></p></td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="account">帐号名称：</label></div>
								</td>
								<td >
									<input id="account" name="account" type="hidden" value="<?php echo $account; ?>" />
									<label ><?php echo $account; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="role_id">帐号类型：</label></div>
								</td>
								<td >
									<select id="role_id" name="role_id">
									
<?php 
    require_once '../proxy/class.databaseproxy.php';
    $array_roles = DatabaseProxy::_DB_VALUE_ACCOUNT_ROLE_NAME();
    for($i=2; $i<count($array_roles); $i++){
        $role_i = $array_roles[$i];
        $echostring = '<option value="'."$i".'"';
        if(!empty($role_id) && $role_id==$i){
            $echostring .= 'selected = "selected"';
        }
        $echostring .= ">$role_i</option>";
        echo $echostring;
    }
?>									

									</select>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="email">电子邮件：</label></div>
								</td>
								<td>
									<input id="email" name="email" type="text" value="<?php echo $email; ?>" />
									<label class="error"><?php echo $err_email; ?></label>
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
				<table>
					<tr>
						<td><label class="error"><?php echo $err_msg;?></label></td>
					</tr>
				</table>
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
