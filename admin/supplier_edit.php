<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "供应商信息修改！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "supplier_manager.php";
$link_name = '返回"供应商基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php
    $err_captcha = "";
    $err_supplier = "";
    $err_msg = "";
if (isset ( $_POST ['submit'] )) 
{
    $supplier = $_POST ['supplier'];
    $type_id = $_POST['type_id'];
    $contact = $_POST ['contact'];
    $email = $_POST ['email'];
    $phone = $_POST ['phone'];
    $notes = $_POST ['notes'];
    $audit_url = $_POST['audit_url'];
    $audit_account = $_POST['audit_account'];
    $audit_pwd = $_POST['audit_pwd'];
    $captcha = sha1 ( $_POST ['captcha'] );

    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    require_once '../utilities/class.suppliermanager.php';
    $mgr = new SupplierManager();
    /*
    if( $mgr->checkSupplier($supplier)){
        $err_supplier = "该供应商山已经存在！";
        $output_form = TRUE;
    }
    */
    
    if (!$output_form){
        if($mgr->updateSupplier($supplier, $type_id,$contact, $email, $phone, $notes,$audit_url, $audit_account, $audit_pwd)){
            $err_msg = '供应商"'.$supplier.'"信息修改成功！';
        }
        else{
            $err_msg = '供应商"'.$supplier.'"信息修改加失败！';
            $output_form = TRUE;
        }
    }
    
}
else {
    $supplier = $_GET ['supplier'];
    $type_id = $_GET['type_id'];
    $contact = $_GET ['contact'];
    $email = $_GET ['email'];
    $phone = $_GET ['phone'];
    $notes = $_GET ['notes'];
    $audit_url = $_GET['audit_url'];
    $audit_account = $_GET['audit_account'];
    $audit_pwd = $_GET['audit_pwd'];
    
    $output_form = TRUE;
}
if ($output_form) {
?>

				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<div align="center">
						<p class="error"><?php echo $err_msg?></p>
						<table>
							<tr>
								<td>
									<div align="right"><label for="supplier">供应商名称：</label></div>
								</td>
								<td >
									<label ><?php echo $supplier; ?></label>
									<input id="supplier" name="supplier" type="hidden" value="<?php echo $supplier; ?>" />
									<label class="error"><?php echo $err_supplier; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="type_id">供应商类型：</label></div>
								</td>
								<td >
									<select id="type_id" name="type_id">
									
<?php 
    require_once '../proxy/class.databaseproxy.php';
    $array_type = DatabaseProxy::_DB_VALUE_SUPPLIER_TYPE();
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
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="email">电子邮件：</label></div>
								</td>
								<td>
									<input id="email" name="email" type="text" value="<?php echo $email; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="phone">联系电话：</label></div>
								</td>
								<td>
									<input id="phone" name="phone" type="text" value="<?php echo $phone; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="audit_url">稽核网址：</label></div>
								</td>
								<td>
									<input id="audit_url" name="audit_url" type="text" value="<?php echo $audit_url; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="audit_account">稽核帐号：</label></div>
								</td>
								<td>
									<input id="audit_account" name="audit_account" type="text" value="<?php echo $audit_account; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="audit_pwd">稽核密码：</label></div>
								</td>
								<td>
									<input id="audit_pwd" name="audit_pwd" type="text" value="<?php echo $audit_pwd; ?>" />
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
					<tr>
						<td><p></p></td>
					</tr>
					<tr>
						<td><a href="supplier_add.php">添加下一个？</a></td>
					</tr>
				</table>
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
