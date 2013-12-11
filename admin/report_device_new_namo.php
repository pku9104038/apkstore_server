<?php 

session_start();
$page_title = "终端注册查询"; 
require_once '../res/header.php';

require '../res/date_model_selector_namo.php';

$page_fetch_limit = 50;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_stamp = DatabaseProxy::DB_COLUMN_REGISTER_STAMP;
$column_model = DatabaseProxy::DB_COLUMN_MODEL;
$column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
$column_imei = DatabaseProxy::DB_COLUMN_IMEI;
$column_province = DatabaseProxy::DB_COLUMN_REGISTER_PROVINCE;
$column_city = DatabaseProxy::DB_COLUMN_REGISTER_CITY;

$session_name = 'report_device_new_namo';
$default_sort = $column_stamp;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';

?>


					<div align="center">
						<table width="100%" border=3>
							<tr>
								<th width="20%">
									<div class="column_name">时间</div>
								</th>
								<th width="20%">
									<div class="column_name">机型</div>
								</th>
								<th width="20%">
									<div class="column_name">IMEI</div>
								</th>
								<th width="20%">
									<div class="column_name">省区</div>
								</th>
								<th width="20%">
									<div class="column_name">城市</div>
								</th>
							</tr>

<?php

$stamp_start = date("Y-m-d H:i:s",strtotime($date_start));
$stamp_end = date("Y-m-d H:i:s",strtotime($date_end)+24*3600);

require_once '../utilities/class.devicemanager.php';
$mgr = new DeviceManager();

$limit = $page_fetch_limit;
$total = $mgr->getDevicesTotalByModel($model_array,$city_array,$stamp_start,$stamp_end);
//$array = $mgr->fetchDevicesByModel($model_array, $date_start, $date_end, 1, 0);
//$total = count($array);
$num_pages = ceil($total/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}

$array = $mgr->fetchDevicesByModel($model_array, $city_array, $stamp_start, $stamp_end, $cur_page, $limit);
foreach ($array as $row){
    
?>

							<tr>
								<td >
									<div class="column_value"><?php echo $row["$column_stamp"];?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $model_names["serial_".$row["$column_model_serial"]];?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $row["$column_imei"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_province"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_city"];?></div>
								</td>
								
							</tr>
    
<?php     
}
require_once '../utilities/class.utilities.php';
$page_links = Utilities::generate_page_links($_SERVER['PHP_SELF'], $sort, $order, $cur_page, $num_pages, $page_index_max);

?>
						</table>
					</div>
					<div align="right">
						<?php echo $page_links;?>
					</div>

<?php 
require_once '../res/footer.php';
?>
