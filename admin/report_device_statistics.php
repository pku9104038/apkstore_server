<?php 
require_once '../admin/check_login.php';
$roles_required = Array(
    2,    //管理员
    5,    //
    6
);
require_once '../admin/role_check.php';

$page_title = "终端时段统计"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "../admin/report_links.php";
$link_name = '返回"统计数据报告"';
require_once '../res/righttop_link.php';

//require '../res/date_model_city_selector.php';
require '../res/selector_period_date_model_city.php';
switch ($period_selected){
	case 1:
		$page_fetch_limit = 10;

		break;

	case 2:
		$page_fetch_limit = 36;

		break;

	case 3:
		$page_fetch_limit = 53;

		break;

	case 4:
		$page_fetch_limit = 31;

		break;
}
//$page_fetch_limit = 100;
$page_index_max = 10;
?>

<?php 
$column_name = "name";
$column_value = 'value';

$session_name = 'report_device_statistics';
$default_sort = $column_name;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require '../admin/sortorder.php';

?>


					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="12%">
									<div class="column_name">序号</div>
								</td>
								<td width="44%">
									<div class="column_name">时间</div>
								</td>
								<td width="44%">
									<div class="column_name">数量</div>
								</td>
							</tr>

<?php
require_once '../utilities/class.devicemanager.php';
$mgr = new DeviceManager();

$stamp_start = date("Y-m-d H:i:s",strtotime($date_start));
$stamp_end = date("Y-m-d H:i:s",strtotime($date_end)+24*3600);

$limit = $page_fetch_limit;
$total = count($period_array);
$num_pages = ceil($total/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}

$array = $mgr->fetchDeviceCountByPeriod($period_array, $model_array, $city_array, $cur_page, $limit,$period_selected);
$count_total = 0;
$count_min = 0;
$count_max = 0;
$area_min = "";
$area_max = "";
$counter = 0;
foreach ($array as $row){
    
?>

							<tr>
								<td >
									<div class="column_value"><?php echo $counter+1;?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $row["$column_name"];?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $row["$column_value"];?></div>
								</td>
								
							</tr>
    
<?php  

	$count_total += $row["$column_value"];
	
	if ($count_max==0 || ($count_min > $row["$column_value"] ) ) {
		$count_min = $row["$column_value"];
		$period_min = $row["$column_name"];
	}
	
	if ($row["$column_value"]>$count_max) {
		$count_max = $row["$column_value"];
		$period_max = $row["$column_name"];
	}

	$counter++;
}
require_once '../utilities/class.utilities.php';
$page_links = Utilities::generate_page_links($_SERVER['PHP_SELF'], $sort, $order, $cur_page, $num_pages, $page_index_max);

$pngfile = "../json/".$_SESSION['account']."_bar.png";
unlink($pngfile);
require '../res/image_bar_array.php';
?>
						</table>
					</div>
					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="12%" >总数：<?php echo $count_total;?></td>
								<td width="12%" >平均：<?php echo ceil($count_total/count($array));?></td>
								<td width="38%" >最大：<?php echo $count_max.'  @ '.$period_max; ?></td>
								<td width="38%" >最小：<?php echo $count_min.'  @ '.$period_min; ?></td>
							</tr>	
						</table>
					</div>
					<div align="center">
						<img alt="bar.png" src="<?php echo $pngfile;?>">
					</div>
					<div align="right">
						<?php echo $page_links;?>
					</div>

<?php 
require_once '../res/footer.php';
?>
