<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "删除型号信息！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "model_manager.php";
$link_name = '返回"型号基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

    $err_captcha = "";
    $err_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $model = $_POST['model'];
    $model_serial = $_POST['model_serial']+0;
    $brand = $_POST['brand'];
    $customer = $_POST['customer'];
    $captcha = sha1 ( $_POST ['captcha'] );
    
    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    
    require_once '../utilities/class.modelmanager.php';
    $mgr = new ModelManager();
   
    if (!$output_form){
        if($mgr->removeModel($model_serial)){
            $err_msg = '型号"'.$model.'"删除成功！';
        }
        else{
            $err_msg = '型号"'.$model.'"删除失败！';
            $output_form = TRUE;
        }
    }
    
}
else if(isset($_POST['cancel'])){
    header("Location: model_manager.php");
}
else {
    $model = $_GET['model'];
    $model_serial = $_GET['model_serial']+0;
    $brand = $_GET['brand'];
    $customer = $_GET['customer'];
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
									<div align="right"><label for="model">型号：</label></div>
								</td>
								<td >
									<label ><?php echo $model; ?></label>
									<input id="model" name="model" type="hidden" value="<?php echo $model; ?>" />
									<input id="model_serial" name="model_serial" type="hidden" value="<?php echo $model_serial; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="brand">品牌：</label></div>
								</td>
								<td >
									<label ><?php echo $brand; ?></label>
									<input id="brand" name="brand" type="hidden" value="<?php echo $brand; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="customer">客户：</label></div>
								</td>
								<td>
									<label ><?php echo $customer; ?></label>
									<input id="customer" name="customer" type="hidden" value="<?php echo $customer; ?>" />
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
