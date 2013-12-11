<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "删除品牌信息！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "brand_manager.php";
$link_name = '返回"品牌基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php
    $err_captcha = "";
    $err_msg = "";


if (isset ( $_POST ['submit'] )) 
{
    $brand = $_POST ['brand'];
    $notes = $_POST ['notes'];
    $captcha = sha1 ( $_POST ['captcha'] );
    
    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    
    require_once '../utilities/class.brandmanager.php';
    $mgr = new BrandManager();
   
    if (!$output_form){
        if($mgr->removeBrand($brand)){
            $err_msg = '品牌"'.$brand.'"删除成功！';
        }
        else{
            $err_msg = '品牌"'.$brand.'"删除失败！';
            $output_form = TRUE;
        }
    }
    
}
else if(isset($_POST['cancel'])){
    header("Location: brand_manager.php");
}
else {
    $brand = $_GET ['brand'];
    $notes = $_GET ['notes'];
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
									<div align="right"><label for="brand">品牌名称：</label></div>
								</td>
								<td >
									<label ><?php echo $brand; ?></label>
									<input id="brand" name="brand" type="hidden" value="<?php echo $brand; ?>" />
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
