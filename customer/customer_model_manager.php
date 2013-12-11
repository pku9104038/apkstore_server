<?php 
require_once '../admin/check_login.php';
$roles_required = Array(
    2,    //管理员
    3,     //客户信息管理员
    7
);
require_once '../admin/role_check.php';

$page_title = "机型管理"; 
require_once '../res/header.php';
require_once '../res/navigator_customer.php';
//$link = "customer_model_add.php";
//$link_name = "添加新型号";
require_once '../res/righttop_link.php';

$page_fetch_limit = 20;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
$column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
$column_model = DatabaseProxy::DB_COLUMN_MODEL;
$column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
$column_notes = DatabaseProxy::DB_COLUMN_MODEL_NOTES;
$column_register_date = DatabaseProxy::DB_COLUMN_MODEL_REGISTER_DATE;

$session_name = 'model';
$default_sort = $column_brand;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require '../admin/sortorder.php';
?>

					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="14%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_model&order=$reorder";?>">型号</a></div>
								</td>
								<td width="14%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_brand_serial&order=$reorder";?>">品牌</a></div>
								</td >
								<td width="14%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_register_date&order=$reorder";?>">注册日期</a></div>
								</td>
								<td width="24%">
									<div class="column_name">备注</div>
								</td>
							</tr>

<?php

$limit = $page_fetch_limit;

require_once '../utilities/class.accountmanager.php';
$accountMgr = new AccountManager();
$brand_array = $accountMgr->getBrandSerialsByCustomer($_SESSION['account']);

require_once '../utilities/class.modelmanager.php';
$mgr = new ModelManager();
$total = count($brand_array);
$num_pages = ceil($total/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}
$array_model = $mgr->fetchModelByBrands($brand_array,$sort, $cur_page, $limit, $order);
foreach ($array_model as $model){
?>

							<tr>
								<td >
									<div class="column_value"><?php echo $model["$column_model"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $model["$column_brand"]?></div>
								</td >
								<td>
									<div class="column_value"><?php echo $model["$column_register_date"]?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $model["$column_notes"]?></div>
								</td>
							</tr>
    
<?php     
}
require_once '../utilities/class.utilities.php';
$page_links = Utilities::generate_page_links($_SERVER['PHP_SELF'], $sort,$order, $cur_page, $num_pages, $page_index_max);

?>
						</table>
					</div>
					<div align="right">
						<?php echo $page_links?>
					</div>
<?php 
require_once '../res/footer.php';
?>
