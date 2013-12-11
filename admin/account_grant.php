<?php 
require_once 'check_login.php';
$roles_required = Array(
    2    //管理员
);
require_once 'role_check.php';

$page_title = "客户授权变更！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "account_manager.php";
$link_name = '返回"帐号管理"';
require_once '../res/righttop_link.php';
?>

<?php
require_once '../proxy/class.databaseproxy.php';
$column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
$column_account_customer_serial = DatabaseProxy::DB_COLUMN_ACCOUNT_CUSTOMER_SERIAL;
$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;

$err_captcha = "";
$err_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $account = $_POST ['account'];
    $customer = $_POST['customer'];
    $old_customer = $_POST['old_customer'];
    $captcha = sha1 ( $_POST ['captcha'] );

    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    require_once '../utilities/class.accountmanager.php';
    $mgr = new AccountManager();
    if (!$output_form){
        require_once '../utilities/class.customermanager.php';
        $custMgr = new CustomerManager();
        $customer_serial =  $custMgr->getCustomerSerial($customer);
        if($customer_serial > 0 && $mgr->grantCustomer($account, $customer_serial)){
            $err_msg = '客户"'.$customer.'"授权修改成功！';
        }
        else{
            $err_msg = '客户"'.$customer.'"授权修改失败！';
            $output_form = TRUE;
        }
    }
    
}
else {
    $account = $_GET['account'];
    $old_customer = $_GET['customer'];
    $customer = $old_customer;
    
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
									<label><?php echo $account; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="customer">客户授权：</label></div>
								</td>
								<td >
									<input id="old_customer" name="old_customer" type="hidden" value="<?php echo $old_customer; ?>" />
									<label><?php echo $old_customer; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="customer">变更授权：</label></div>
								</td>
								<td >
									<select id="customer" name="customer">
									
<?php 
    require_once '../utilities/class.customermanager.php';
    $mgrCustomer = new CustomerManager();
    $array_customer = $mgrCustomer->getCustomers();
    
    foreach ($array_customer as $customer_name){
        $echostring = '<option value="'.$customer_name["$column_customer"].'"';
        if(!empty($customer) && $customer==$customer_name["$column_customer"]){
            $echostring .= 'selected = "selected"';
        }
        $echostring .= '>'.$customer_name["$column_customer"].'</option>';
        echo $echostring;
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
