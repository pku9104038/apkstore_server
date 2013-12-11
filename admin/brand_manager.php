<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "终端品牌信息管理！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "brand_add.php";
$link_name = "添加新品牌";
require_once '../res/righttop_link.php';

$page_fetch_limit = 20;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
$column_notes = DatabaseProxy::DB_COLUMN_CUSTOMER_NOTES;
$column_register_date = DatabaseProxy::DB_COLUMN_BRAND_REGISTER_DATE;


$session_name = 'brand';
$default_sort = $column_brand;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';

?>


					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="20%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_brand&order=$reorder"; ?>">品牌名称</a></div>
								</td>
								<td width="20%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_customer&order=$reorder"; ?>">客户名称</a></div>
								</td>
								<td width="20%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_register_date&order=$reorder"; ?>">日期</a></div>
								</td>
								<td width="20%">
									<div class="column_name">备注</div>
								</td>
								<td></td>
							</tr>

<?php
require_once '../utilities/class.brandmanager.php';
$mgr = new BrandManager();

$limit = $page_fetch_limit;
$total_brands = $mgr->getTotal();
$num_pages = ceil($total_brands/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}
$array_brands = $mgr->fetchBrands($sort, $cur_page, $limit, $order);
foreach ($array_brands as $brand){
?>

							<tr>
								<td >
									<div class="column_value"><?php echo $brand["$column_brand"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $brand["$column_customer"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $brand["$column_register_date"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $brand["$column_notes"];?></div>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="brand_edit.php'
            .'?brand='.$brand["$column_brand"]
            .'&customer='.$brand["$column_customer"]
            .'&customer_serial='.$brand["$column_customer_serial"]
            .'&notes='.$brand["$column_notes"]
            .'">修改</a></div>';
?>
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
