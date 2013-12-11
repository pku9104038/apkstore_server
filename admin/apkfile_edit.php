<?php
 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "应用文件版本信息更新！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "apkfile_manager.php";
$link_name = '返回"应用文件版本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

$err_captcha = "";
$err_category = "";
$err_msg = "";
$upload_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $serial = $_POST ['serial'];
    $supplier_serial = $_POST ['supplier_serial'];
    $application = $_POST['application'];
    $vercode = $_GET['vercode'];
    $icon = $_POST['icon'];
    $notes = $_POST['notes'];
    $captcha = sha1 ( $_POST ['captcha'] );
   
    $output_form = FALSE;

    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    require_once '../utilities/class.apkfilemanager.php';
    $mgr = new ApkfileManager();
    $update = $mgr->updateApkfileSupplierNotes($serial, $supplier_serial,$notes); 
    $err_msg = '应用版本"'.$vercode.'"信息修改';
        
    if($update){
        $err_msg .= '成功！';
    }
    else{
        $err_msg .= '失败！';
    }
    
    
}
else {
    $serial = $_GET ['serial'];
    $supplier_serial = $_GET ['supplier_serial'];
    $application = $_GET['application'];
    $vercode = $_GET['vercode'];
    $icon = $_GET['icon'];
    $notes = $_GET['notes'];
    $output_form = TRUE;
}
if ($output_form) {
    require_once '../res/url_conf.php';
    require_once '../proxy/class.databaseproxy.php';
    $column_supplier_serial = DatabaseProxy::DB_COLUMN_SUPPLIER_SERIAL;
    $column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
    
?>

				<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
					<div align="center">
						<p class="error"><?php echo $err_msg?></p>
						<p class="error"><?php echo $upload_msg?></p>
						<table>
							<tr>
								<td><div align="right"><label for="icon">图标：</label></div></td>
								<td>
									<img alt="图标" src="<?php echo $path_apkicons.$icon;?>" width="48" height="48">
									<input id="icon" name="icon" type="hidden" value="<?php echo $icon; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="application">应用名称：</label></div>
								</td>
								<td ><?php echo $application;?>
									<input id="application" name="application" type="hidden" value="<?php echo $application; ?>" />
									<input id="serial" name="serial" type="hidden" value="<?php echo $serial; ?>" />
									<input id="vercode" name="vercode" type="hidden" value="<?php echo $vercode; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="supplier_serial">供应商：</label></div>
								</td>
								<td >
									<select id="supplier_serial" name="supplier_serial">
<?php 

    require_once '../utilities/class.suppliermanager.php';
    $supplierMgr = new SupplierManager();
    $array_supplier = $supplierMgr->getSuppliers();
    for($i=0; $i<count($array_supplier); $i++){
        $strSelect = '<option value="'.$array_supplier[$i]["$column_supplier_serial"].'"';
        if(!empty($supplier_serial) && $supplier_serial==$array_supplier[$i]["$column_supplier_serial"]){
            $strSelect .= 'selected = "selected"';
            $supplier = $array_supplier[$i]["$column_supplier"];
            $supplier_serial = $array_supplier[$i]["$column_supplier_serial"];
        }
        $strSelect .= '>'.$array_supplier[$i]["$column_supplier"].'</option>';
        echo $strSelect;
    }

?>
									</select>
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
					<p class="error"><?php echo $err_msg;?></p>
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
