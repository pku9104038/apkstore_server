<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "客户信息修改！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "customer_manager.php";
$link_name = '返回"客户基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

    $err_captcha = "";
    $err_customer = "";
    $err_contact = "";
    $err_email = "";
    $err_phone = "";
    $err_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $customer = $_POST ['customer'];
    $type_id = $_POST['type_id'];
    $contact = $_POST ['contact'];
    $email = $_POST ['email'];
    $phone = $_POST ['phone'];
    $notes = $_POST ['notes'];
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
    
    require_once '../utilities/class.customermanager.php';
    $mgr = new CustomerManager();
    
    if (!$output_form){
        if($mgr->updateCustomer($customer, $type_id, $contact, $email, $phone, $notes)){
            $err_msg = '客户"'.$customer.'"信息更新成功！';
        }
        else{
            $err_msg = '客户"'.$customer.'"信息更新失败！';
            $output_form = TRUE;
        }
    }
    
}
else {
    $customer = $_GET ['customer'];
    $type_id = $_GET['type_id'];
    $contact = $_GET ['contact'];
    $email = $_GET ['email'];
    $phone = $_GET ['phone'];
    $notes = $_GET ['notes'];
    $captcha = "";
    
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
									<div align="right"><label for="customer">客户名称：</label></div>
								</td>
								<td >
									<label ><?php echo $customer; ?></label>
									<input id="customer" name="customer" type="hidden" value="<?php echo $customer; ?>" />
									<label class="error"><?php echo $err_customer; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="type_id">客户类型：</label></div>
								</td>
								<td >
									<select id="type_id" name="type_id">
									
<?php 
    require_once '../proxy/class.databaseproxy.php';
    $array_type = DatabaseProxy::_DB_VALUE_CUSTOMER_TYPE();
    for($i=1; $i<count($array_type); $i++){
        $type_i = $array_type[$i];
        $echostring = '<option value="'."$i".'"';
        if(!empty($type_id) && $type_id==$i){
            $echostring .= 'selected = "selected"';
        }
        $echostring .= ">$type_i</option>";
        echo $echostring;
    }
?>									

									</select>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="contact">联系人：</label></div>
								</td>
								<td>
									<input id="contact" name="contact" type="text" value="<?php echo $contact; ?>" />
									<label class="error"><?php echo $err_contact; ?></label>
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
								<td>
									<div align="right"><label for="phone">联系电话：</label></div>
								</td>
								<td>
									<input id="phone" name="phone" type="text" value="<?php echo $phone; ?>" />
									<label class="error"><?php echo $err_phone; ?></label>
								</td>
							</tr>
							<tr>
								<td><div align="right"><label for="notes">备注：</label></div></td>
								<td>
									<textarea id="notes" name="notes" rows="4" cols="40" ><?php echo $notes; ?></textarea>
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
