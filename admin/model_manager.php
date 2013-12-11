<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "型号基本信息管理！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "model_add.php";
$link_name = "添加新型号";
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
require 'sortorder.php';
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
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_customer_serial&order=$reorder";?>">客户</a></div>
								</td>
								<td width="14%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_register_date&order=$reorder";?>">注册日期</a></div>
								</td>
								<td width="24%">
									<div class="column_name">备注</div>
								</td>
								<td></td>
								<td></td>
							</tr>

<?php

$limit = $page_fetch_limit;

require_once '../utilities/class.modelmanager.php';
$mgr = new ModelManager();
$total_model = $mgr->getTotal();
$num_pages = ceil($total_model/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}
$array_model = $mgr->fetchModel($sort, $cur_page, $limit, $order);
foreach ($array_model as $model){
?>

							<tr>
								<td >
									<div class="column_value"><?php echo $model["$column_model"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $model["$column_brand"]?></div>
								</td >
								<td >
									<div class="column_value"><?php echo $model["$column_customer"]?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $model["$column_register_date"]?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $model["$column_notes"]?></div>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="model_edit.php'
            .'?model_serial='.$model["$column_model_serial"]
            .'&model='.$model["$column_model"]
            .'&brand_serial='.$model["$column_brand_serial"]
            .'&customer_serial='.$model["$column_customer_serial"]
            .'&notes='.$model["$column_notes"]
            .'">修改</a></div>';
?>
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
