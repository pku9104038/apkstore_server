<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "删除客户信息！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "supplier_manager.php";
$link_name = '返回"客户基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

    $err_captcha = "";
    $err_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $supplier = $_POST ['supplier'];
    $type = $_POST['type'];
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
   
    if (!$output_form){
        if($mgr->removeSupplier($supplier)){
            $err_msg = '供应商"'.$supplier.'"删除成功！';
        }
        else{
            $err_msg = '供应商"'.$supplier.'"删除失败！';
            $output_form = TRUE;
        }
    }
    
}
else if(isset($_POST['cancel'])){
    header("Location: supplier_manager.php");
}
else {
    $supplier = $_GET ['supplier'];
    $type = $_GET['type'];
    $contact = $_GET ['contact'];
    $email = $_GET ['email'];
    $phone = $_GET ['phone'];
    $notes = $_GET ['notes'];
    $audit_url = $_GET['audit_url'];
    $audit_account = $_GET['audit_account'];
    $audit_pwd = $_GET['audit_pwd'];

    $captcha = "";
    
    
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
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="type">客户类型：</label></div>
								</td>
								<td >
									<label ><?php echo $type; ?></label>
									<input id="type" name="type" type="hidden" value="<?php echo $type; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="contact">联系人：</label></div>
								</td>
								<td>
									<label ><?php echo $contact; ?></label>
									<input id="contact" name="contact" type="hidden" value="<?php echo $contact; ?>" />
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
								<td>
									<div align="right"><label for="phone">联系电话：</label></div>
								</td>
								<td>
									<input id="phone" name="phone" type="hidden" value="<?php echo $phone; ?>" />
									<label "><?php echo $phone; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="audit_url">稽核网址：</label></div>
								</td>
								<td>
									<label ><?php echo $audit_url; ?></label>
									<input id="audit_url" name="audit_url" type="hidden" value="<?php echo $audit_url; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="audit_account">稽核帐号：</label></div>
								</td>
								<td>
									<label ><?php echo $audit_account; ?></label>
									<input id="audit_account" name="audit_account" type="hidden" value="<?php echo $audit_account; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="audit_pwd">稽核密码：</label></div>
								</td>
								<td>
									<input id="audit_pwd" name="audit_pwd" type="hidden" value="<?php echo $audit_pwd; ?>" />
									<label "><?php echo $audit_pwd; ?></label>
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
