<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "添加新品牌！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "brand_manager.php";
$link_name = '返回"品牌基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php
require_once '../proxy/class.databaseproxy.php';
$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;


    $err_msg = "";
    $err_captcha = "";
    $err_brand = "";
    $err_captcha = "";
    $brand = "";
    $notes = "";
    $output_form = TRUE;

if (isset ( $_POST ['submit'] )) 
{
    $brand = $_POST ['brand'];
    $customer_serial = $_POST['customer_serial'];
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
    if( $mgr->checkBrand($brand)){
        $err_brand = "该品牌已经存在！";
        $output_form = TRUE;
    }
    
    
    if (!$output_form){
        if($mgr->addBrand($brand, $notes, $customer_serial)){
            $err_msg = '新品牌"'.$brand.'"添加成功！';
        }
        else{
            $err_msg = '新品牌"'.$brand.'"添加失败！';
            $output_form = TRUE;
        }
    }
    
}
else {
/*
    $err_msg = "";
    $err_captcha = "";
    $err_brand = "";
    $err_captcha = "";
    $brand = "";
    $notes = "";
    $output_form = TRUE;
*/
}
if ($output_form) {
?>

				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<div align="center">
						<p class="error"><?php echo $err_msg?></p>
						<table>
							<tr>
								<td>
									<div align="right"><label for="customer_serial">客户：</label></div>
								</td>
								<td >
									<select id="customer_serial" name="customer_serial">
									
<?php 
    require_once '../utilities/class.customermanager.php';
    $customerMgr = new CustomerManager();
    $array_customers = $customerMgr->getCustomers();
    for($i=0; $i<count($array_customers); $i++){
        $echostring = '<option value="'.$array_customers[$i]["$column_customer_serial"].'"';
        if(!empty($customer_serial) && $customer_serial==$array_customers[$i]["$column_customer_serial"]){
            $echostring .= 'selected = "selected"';
        }
        $echostring .= '>'.$array_customers[$i]["$column_customer"].'</option>';
        echo $echostring;
    }
    
?>									

									</select>
								</td>
							</tr>
						    
						    <tr>
								<td>
									<div align="right"><label for="brand">品牌名称：</label></div>
								</td>
								<td >
									<input id="brand" name="brand" type="text" value="<?php echo $brand; ?>" />
									<label class="error"><?php echo $err_brand; ?></label>
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
						<td><a href="brand_add.php">添加下一个？</a></td>
					</tr>
				</table>
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
