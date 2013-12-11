<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "客户基本信息管理！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "customer_add.php";
$link_name = "添加新客户";
require_once '../res/righttop_link.php';

$page_fetch_limit = 10;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
$column_type_id = DatabaseProxy::DB_COLUMN_CUSTOMER_TYPE_ID;
$column_contact = DatabaseProxy::DB_COLUMN_CUSTOMER_CONTACT;
$column_email = DatabaseProxy::DB_COLUMN_CUSTOMER_EMAIL;
$column_phone = DatabaseProxy::DB_COLUMN_CUSTOMER_PHONE;
$column_notes = DatabaseProxy::DB_COLUMN_CUSTOMER_NOTES;
$column_register_date = DatabaseProxy::DB_COLUMN_CUSTOMER_REGISTER_DATE;

$session_name = 'customer';
$default_sort = $column_type_id;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';
?>

					<div align="center">
						<table width="100%" border="3">
							<tr>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_customer&order=$reorder";?>">客户名称</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_type_id&order=$reorder";?>">客户类型</a></div>
								</td >
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_contact&order=$reorder";?>">联系人</a></div>
								</td>
								<td width="16%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_email&order=$reorder";?>">电子邮箱</a></div>
								</td>
								<td width="12%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_phone&order=$reorder";?>">电话</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_register_date&order=$reorder";?>">注册日期</a></div>
								</td>
								<td width="25%">
									<div class="column_name">备注</div>
								</td>
								<td></td>
								<td></td>
							</tr>

<?php
require_once '../utilities/class.customermanager.php';
$mgr = new CustomerManager();


$limit = $page_fetch_limit;
$total_customers = $mgr->getTotal();
$num_pages = ceil($total_customers/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}
$array_customers = $mgr->fetchCustomers($sort, $cur_page, $limit, $order);
$array_customer_types = DatabaseProxy::_DB_VALUE_CUSTOMER_TYPE();
foreach ($array_customers as $customer){
?>

							<tr>
								<td >
									<div class="column_value"><?php echo $customer["$column_customer"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $array_customer_types[$customer["$column_type_id"]]?></div>
								</td >
								<td >
									<div class="column_value"><?php echo $customer["$column_contact"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $customer["$column_email"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $customer["$column_phone"]?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $customer["$column_register_date"]?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $customer["$column_notes"]?></div>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="customer_edit.php'
            .'?customer='.$customer["$column_customer"]
            .'&type_id='.$customer["$column_type_id"]
            .'&contact='.$customer["$column_contact"]
            .'&email='.$customer["$column_email"]
            .'&phone='.$customer["$column_phone"]
            .'&notes='.$customer["$column_notes"]
            .'">修改</a></div>';
?>
								</td>
								<td>
<?php 
    echo  '<div class="column_value_center"><a href="customer_remove.php'
            .'?customer='.$customer["$column_customer"]
            .'&type='.$array_customer_types[$customer["$column_type_id"]]
            .'&contact='.$customer["$column_contact"]
            .'&email='.$customer["$column_email"]
            .'&phone='.$customer["$column_phone"]
            .'&notes='.$customer["$column_notes"]
            .'">删除</a></div>';
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
