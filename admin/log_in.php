<?php 
require_once './restore_cookie.php';
$page_title = "帐号登录"; 
require_once '../res/header.php';
echo '<hr />';

//require_once '../res/navigator.php';

$link = "reset_pwd.php";
$link_name = "找回帐号密码";
require_once '../res/righttop_link.php';

?>

<?php
    $err_captcha = "";
    $err_account = "";
    $err_password = "";
    $err_msg = "";
    
if (isset ( $_POST ['log_in'] )) 
{
    $account = $_POST ['account'];
    $pwd = $_POST ['pwd'];
    $captcha = sha1 ( $_POST ['captcha'] );

    $output_form = false;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = true;
    }
    // test bypass loging
    /*
    else{
    	$_SESSION['role_id'] = 2;
    	$roles = DatabaseProxy::_DB_VALUE_ACCOUNT_ROLE_NAME();
    	$role = $roles[$_SESSION['role_id']+0];
    	$_SESSION['role'] = $role;
    	$_SESSION['account'] = $account;
    	setcookie('account', $account, time()+(60*60*24*7));
    	
    	$_SESSION['login_times'] = $login_times;
    	$_SESSION['login_latest'] = $login_latest;
    	 
    }

    */
    
    //check account and password
    
    require_once '../utilities/class.accountmanager.php';
    $mgr = new AccountManager ();
    
    $err_code = AccountManager::ERR_CODE;
    $login_checked = $mgr->checkLogin ( $account, $pwd );
    
    if ($login_checked["$err_code"] > AccountManager::ERR_NONE) {
        $output_form = true;
        switch ($login_checked["$err_code"]) {
            case AccountManager::ERR_DATABASE :
                $err_msg = "数据库连接失败！";
                break;

            case AccountManager::ERR_ACCOUNT :
                $err_account = "无效帐号名称！";
                break;
                
            case AccountManager::ERR_PASSWORD :
                $err_password = "密码输入错误！";
                break;
        }
    }
    else {
        require_once '../proxy/class.databaseproxy.php';
        $column_login_times = DatabaseProxy::DB_COLUMN_ACCOUNT_LOGIN_TIMES;
        $column_login_latest = DatabaseProxy::DB_COLUMN_ACCOUNT_LOGIN_LATEST;
        $column_role_id = DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID;
        $role_id = $login_checked["$column_role_id"];
        $login_times = $login_checked["$column_login_times"];
        $login_latest = $login_checked["$column_login_latest"];
        $customer_serial = $mgr->getAccountCustomerSerial($account);
        $_SESSION['customer_serial'] = $customer_serial;
        
        $_SESSION['role_id'] = $role_id;
        $roles = DatabaseProxy::_DB_VALUE_ACCOUNT_ROLE_NAME();
        $role = $roles[$_SESSION['role_id']+0];
        $_SESSION['role'] = $role;
        $_SESSION['account'] = $account;
        setcookie('account', $account, time()+(60*60*24*7));
        
        $_SESSION['login_times'] = $login_times;
        $_SESSION['login_latest'] = $login_latest;
    }
    
    
}
else {
    $account = $_SESSION['account'];
    $pwd = "";
    
    
    $output_form = true;
}
if ($output_form) {
?>
		<hr />
				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<div align="center">
						<table>
							<tr>
								<td></td>
								<td><p class="error"><?php echo $err_msg?></p></td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="account">帐号：</label></div>
								</td>
								<td>
									<input id="account" name="account" type="text" value="<?php echo $account; ?>" />
								</td>
								<td>
									<label class="error"><?php echo $err_account; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="pwd">密码：</label></div>
								</td>
								<td>
									<input id="pwd" name="pwd" type="password" value="<?php echo $pwd; ?>" />
								</td>
								<td>
									<label class="error"><?php echo $err_password; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="captcha">验证码：</label></div>
								</td>
								<td>
									<input id="captcha" name="captcha" type="text" value="" />
								</td>
								<td>
									<img src="../res/captcha.php" alt="验证码" />
									<label class="error"><?php echo $err_captcha; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<p></p>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div align="left"><input type="submit" name="log_in" value="帐号登录" /></div>
								</td>
							</tr>
						</table>
					</div>
				</form>

<?php
} else if (! $output_form) {
	if($role_id+0 < 7){
		header('Location: admin_home.php');
	}
	else{
		header('Location: ../customer/customer_home.php');
	}
}
?>



<?php 
require_once '../res/footer.php';
?>
