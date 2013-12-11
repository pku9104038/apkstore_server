<?php 
require_once '../admin/check_login.php';
$roles_required = Array(
    2,    //管理员
    5,    //
    6
);
require_once '../admin/role_check.php';

$page_title = "应用下载统计"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "report_links.php";
$link_name = '返回"统计数据报告"';
require_once '../res/righttop_link.php';

require '../res/selector_date_app.php';


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

$session_name = 'report_download_app';
$default_sort = $column_stamp;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';

?>


					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="12%" >
									<div class="column_name">序号</div>
								</td>
								<td width="44%">
									<div class="column_name">应用名称</div>
								</td>
								<td width="44%">
									<div class="column_name">下载次数</div>
								</td>
							</tr>

<?php

//require_once '../utilities/class.utilities.php';
$stamp_start = date("Y-m-d H:i:s",strtotime($date_start));//Utilities::getUTCStampStartFromDateCN($date_start);
$stamp_end = date("Y-m-d H:i:s",strtotime($date_end)+24*3600);//Utilities::getUTCStampEndFromDateCN($date_end);

require_once '../utilities/class.downloadmanager.php';
$mgr = new DownloadManager();

$limit = $page_fetch_limit;
$array = $mgr->fetchDownloadCountByApp($app_array, $stamp_start, $stamp_end, 1, 1000);
foreach ($array as $key => $row) {
	$value[$key]  = $row['value'];
	$name[$key] = $row['name'];
}
// 将数据根据 value 降序排列，根据 name 升序排列
// 把 $data 作为最后一个参数，以通用键排序
array_multisort($value, SORT_DESC, $name, SORT_ASC, $array);
$total = count($array);
$num_pages = ceil($total/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}

//$array = $mgr->fetchDownloadCountByApp($app_array, $stamp_start, $stamp_end, 1, 1000);


$skip = ($cur_page - 1) * $limit;
if($skip <0){
	$skip = 0;
}
$stop = $skip + $limit;
if($stop>count($array)){
	$stop = count($array);
}
if($skip>$stop){
	$skip = $stop;
}

$column_name = "name";
$column_value = "value";
$index=0;
for ($counter=$skip; $counter<$stop; $counter++){
$row['value'] = $array[$counter]['value'];
$row['name'] = $array[$counter]['name'];

//foreach ($array as $row){
    
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
