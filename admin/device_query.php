<?php
session_start();
$page_title = "终端IMEI注册查询";
require_once '../res/header.php';

if (isset ( $_POST ['submit'] )) 
{
    $imei = $_POST ['imei'];
    require_once '../utilities/class.devicemanager.php';
    $mgr = new DeviceManager();
	$array = $mgr->getDeviceInfo($imei);
	    
    
}
else {
	$array = false;
}

require_once '../proxy/class.databaseproxy.php';
if($array){
	$cloumn_model = DatabaseProxy::DB_COLUMN_MODEL;
	$column_stamp = DatabaseProxy::DB_COLUMN_REGISTER_STAMP;
	$column_province = DatabaseProxy::DB_COLUMN_REGISTER_PROVINCE;
	$column_city = DatabaseProxy::DB_COLUMN_REGISTER_CITY;
	$model = $array[0]["$cloumn_model"];
	$stamp = $array[0]["$column_stamp"];
	$province = $array[0]["$column_province"];
	$city = $array[0]["$column_city"];
}
else{
	$stamp = $model  = $province = $city = "未知";
}

?>
		<div align="center">
				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
						<table>
							<tr>
								<td><div align="right"><label for="imei">IMEI：</label></div></td>
								<td><div align="left">
									<input id="imei" name="imei" type="text" value="<?php echo $imei; ?>" />
									</div></td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="model">产品型号：</label></div>
								</td>
								<td >
									<label ><?php echo $model; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="stamp">激活时间：</label></div>
								</td>
								<td >
									<label ><?php echo $stamp; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="province">省区：</label></div>
								</td>
								<td >
									<label ><?php echo $province; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="city">城市：</label></div>
								</td>
								<td >
									<label ><?php echo $city; ?></label>
								</td>
							</tr>
							<tr>
								<td></td>
								<td><div align="left"><input type="submit" name="submit" value="查询" /></div></td>
							</tr>
						</table>
				</form>
		</div>

<?php 
require_once '../res/footer.php';
?>