<?php 
//require_once '../admin/check_login.php';
$page_title = "找回帐号密码！"; 
require_once '../res/header.php';
//require_once '../res/navigator.php';
?>

<?php 
$link = "../admin/log_in.php";
$link_name = "帐号登录";
require_once '../res/righttop_link.php';
?>


<?php
$err_msg = "";
    $err_captcha = "";
    $err_email = "";
if (isset ( $_POST ['reset_pwd'] )) 
{
    $email = $_POST ['email'];
    $captcha = sha1 ( $_POST ['captcha'] );
    
    $output_form = false;
    
    session_start ();
    if ($captcha != $_SESSION ['captcha']) 
    {
        $err_captcha = "重新输入验证码！";
        $output_form = true;
    }
    else
    {
        require_once '../utilities/class.accountmanager.php';
        $mgr = new AccountManager();
        if ($mgr->checkAccountEmail ( $email )) 
        {
            $mgr->resetPasswordByEmail($email);
        }
        else
        {
            $output_form = true;
            $err_email = "帐号邮箱无效！";
        }
    }
} 
else 
{
    $output_form = true;
}

if ($output_form) 
{

?>
				<form method="post" action="<?php	echo $_SERVER ['PHP_SELF'];	?>">
					<div align="center">
						<table>
							<tr>
								<td></td>
								<td><p class="error"><?php echo $err_msg?></p></td>
							</tr>
							<tr>
								<td>
									<div align="right">
										<label for="email">电子邮件：</label>
									</div>
								</td>
								<td>
					  			<input id="email" name="email" type="text" value="<?php echo $email;?>" />
								</td>
								<td>
				  				<label class="error"><?php echo $err_email; ?></label>
								</td>
				  		</tr>
							<tr>
								<td>
									<div align="right">
										<label for="captcha">验证码： </label>
									</div>
								</td>
								<td>
									<input id="captcha" name="captcha" type="text" value="" />
								</td>
								<td>
									<img src="../res/captcha.php" alt="验证码" />
								</td>
								<td> 
									<label class="error"><?php echo $err_captcha;	?></label>
								</td>
							</tr>
							<tr>
								<td><p></p></td>
				  		</tr>
							<tr>
			    			<td></td>
								<td>
									<div align="left">
										<input type="submit" name="reset_pwd" value="找回帐号密码" />
									</div>
								</td>
							</tr>
						</table>
					</div>
				</form>
			
<?php
} 
else if (! $output_form) 
{
?>

				<div align="center">
					<p>密码已经重置，并发送邮件到注册邮箱！ <br /></p>
				</div>

<?php
}
?>


<?php 
require_once '../res/footer.php';
?>
