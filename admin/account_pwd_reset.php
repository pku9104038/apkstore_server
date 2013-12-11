<?php 
require_once 'check_login.php';
$roles_required = Array(
    2     //管理员
);
require_once 'role_check.php';

$page_title = "帐号密码重置！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "account_manager.php";
$link_name = '返回"帐号管理"';
require_once '../res/righttop_link.php';
?>

<?php

$err_captcha = "";
$err_msg = "";
if (isset ( $_POST ['submit'] )) 
{
    $account = $_POST ['account'];
    $role = $_POST['role'];
    $customer = $_POST ['customer'];
    $email = $_POST ['email'];
    $captcha = sha1 ( $_POST ['captcha'] );
    
    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }
    else{
        require_once '../utilities/class.accountmanager.php';
        $mgr = new AccountManager();
   
        if (!$output_form){
            if($mgr->resetPasswordByEmail($email)){
                $err_msg = '帐号"'.$account.'"密码重置成功，并发送到邮箱'.$email.'！';
            }
            else{
                $err_msg = '帐号"'.$account.'"密码重置成功，并发送到邮箱'.$email.'！';
                //                $output_form = TRUE;
            }
        }
    }
    
}
else if(isset($_POST['cancel'])){
    header("Location: account_manager.php");
}
else {
    $account = $_GET['account'];
    $role = $_GET['role'];
    $email = $_GET['email'];
    $customer = $_GET['customer'];
    $captcha = "";
    
    $err_captcha = "";
    
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
									<label ><?php echo $account; ?></label>
									<input id="account" name="account" type="hidden" value="<?php echo $account; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="role">帐号类型：</label></div>
								</td>
								<td>
									<input id="role" name="role" type="hidden" value="<?php echo $role; ?>" />
									<label "><?php echo $role; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="customer">客户名称：</label></div>
								</td>
								<td >
									<label ><?php echo $customer; ?></label>
									<input id="customer" name="customer" type="hidden" value="<?php echo $customer; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="email">电子邮件：</label></div>
								</td>
								<td>
									<label ><?php echo $email; ?></label>
									<input id="email" name="email" type="hidden" value="<?php echo $email; ?>" />
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
