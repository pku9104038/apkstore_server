<?php 
require_once 'check_login.php';
$page_title = "密码修改！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
?>

<div align="center">
	<table width="800" border="0">
		<tr>
			<td width="36%">
				<div align="right">
            <?php require_once '../res/logo_left.php';?>
				</div>
			</td>
			<td>

<?php
    $err_captcha = "";
    $err_pwd = "";
    $err_new_pwd = "";
    $err_new_pwd2 = "";

if (isset ( $_POST ['submit'] )) 
{
    $pwd = $_POST ['pwd'];
    $new_pwd = $_POST ['new_pwd'];
    $new_pwd2 = $_POST ['new_pwd2'];
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
        $mgr = new AccountManager ();
        $account = $_SESSION['account'];
        $login_checked = $mgr->checkLogin ( $account, $pwd );
        $err_code = AccountManager::ERR_CODE;
        if ($login_checked["$err_code"] > AccountManager::ERR_NONE) {
            $output_form = true;
            switch ($login_checked["$err_code"]) {
                case AccountManager::ERR_DATABASE :
                    $err_msg = "数据库连接失败！";
                    break;
    
                case AccountManager::ERR_ACCOUNT :
                    $err_msg = "无效帐号名称！";
                    break;
                    
                case AccountManager::ERR_PASSWORD :
                    $err_pwd = "密码输入错误！";
                    break;
            }
        }
        else{
            if (!AccountManager::pre_matchPassword($new_pwd)){
                $err_new_pwd = "新密码不符合规则！";
                $output_form = true;
            }
            else {
                if ($new_pwd2 != $new_pwd){
                    $err_new_pwd2 = "确认密码不匹配！";
                    $output_form = true;
                }
                else{
                    $account = $_SESSION['account'];
                    if ($mgr->setPassword($account,$new_pwd)){
                        setcookie('role_id', $_SESSION['role_id'], time()-3600);
                        setcookie('account', $_SESSION['account'], time()-3600);
                    }
                    else{
                        $err_msg = "密码变更失败！";
                        $output_form = true;
                    }
                }
            }
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
					<div align="left">
						<table>
							<tr>
								<td>
									<div align="right">
										<label for="pwd">输入原密码：</label>
									</div>
								</td>
								<td>
					  			<input id="pwd" name="pwd" type="password" value="<?php echo $pwd;?>" />
								</td>
								<td>
				  				<label class="error"><?php echo $err_pwd; ?></label>
								</td>
				  		</tr>
							<tr>
								<td>
									<div align="right">
										<label for="new_pwd">输入新密码：</label>
									</div>
								</td>
								<td>
					  			<input id="new_pwd" name="new_pwd" type="password" value="<?php echo $new_pwd;?>" />
								</td>
								<td>
				  				<label class="error"><?php echo $err_new_pwd; ?></label>
								</td>
				  		</tr>
				  		<tr>
								<td></td>
								<td><label class="comment">英文字母、数字构成，6~16位</label></td>
							</tr>
							<tr>
								<td>
									<div align="right">
										<label for="new_pwd2">确认新密码：</label>
									</div>
								</td>
								<td>
					  			<input id="new_pwd2" name="new_pwd2" type="password" value="<?php echo $new_pwd2;?>" />
								</td>
								<td>
				  				<label class="error"><?php echo $err_new_pwd2; ?></label>
								</td>
				  		</tr>
							<tr>
								<td>
									<div align="right">
										<label for="captcha">输入验证码： </label>
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
										<input type="submit" name="submit" value="确认变更" />
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
					<p>密码变更成功！ <br /></p>
				</div>

<?php
}
?>

			</td>
		</tr>
	</table>
</div>


<?php 
require_once '../res/footer.php';
?>
