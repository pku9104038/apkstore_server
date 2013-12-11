<<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理
);
require_once 'role_check.php';

$page_title = "供应商基本信息管理！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "supplier_add.php";
$link_name = "添加新供应商";
require_once '../res/righttop_link.php';

$page_fetch_limit = 10;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
$column_type_id = DatabaseProxy::DB_COLUMN_SUPPLIER_TYPE_ID;
$column_contact = DatabaseProxy::DB_COLUMN_SUPPLIER_CONTACT;
$column_email = DatabaseProxy::DB_COLUMN_SUPPLIER_EMAIL;
$column_phone = DatabaseProxy::DB_COLUMN_SUPPLIER_PHONE;
$column_audit_url = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_URL;
$column_audit_account = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_ACCOUNT;
$column_audit_pwd = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_PWD;
$column_notes = DatabaseProxy::DB_COLUMN_CUSTOMER_NOTES;
$column_register_date = DatabaseProxy::DB_COLUMN_CUSTOMER_REGISTER_DATE;

$session_name = 'supplier';
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
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_supplier&order=$reorder";?>">供应商名称</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_type_id&order=$reorder";?>">供应商类型</a></div>
								</td >
								<td width="5%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_contact&order=$reorder";?>">联系人</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_email&order=$reorder";?>">电子邮箱</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_phone&order=$reorder";?>">电话</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_audit_url&order=$reorder";?>">稽核网址</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_audit_account&order=$reorder";?>">稽核帐号</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_audit_pwd&order=$reorder";?>">稽核密码</a></div>
								</td>
								<td width="8%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_register_date&order=$reorder";?>">注册日期</a></div>
								</td>
								<td width="12%">
									<div class="column_name">备注</div>
								</td>
								<td>
									<label> </label>
								</td>
								<td>
									<label> </label>
								</td>
							</tr>

<?php
require_once '../utilities/class.suppliermanager.php';
$mgr = new SupplierManager();

$limit = $page_fetch_limit;
$total_suppliers = $mgr->getTotal();
$num_pages = ceil($total_suppliers/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}
$array_suppliers = $mgr->fetchSuppliers($sort, $cur_page, $limit, $order);
$array_supplier_types = DatabaseProxy::_DB_VALUE_SUPPLIER_TYPE();
foreach ($array_suppliers as $supplier){
?>

							<tr>
								<td >
									<div class="column_value"><?php echo $supplier["$column_supplier"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $array_supplier_types[$supplier["$column_type_id"]]?></div>
								</td >
								<td >
									<div class="column_value"><?php echo $supplier["$column_contact"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $supplier["$column_email"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $supplier["$column_phone"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $supplier["$column_audit_url"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $supplier["$column_audit_account"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $supplier["$column_audit_pwd"]?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $supplier["$column_register_date"]?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $supplier["$column_notes"]?></div>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="supplier_edit.php'
            .'?supplier='.$supplier["$column_supplier"]
            .'&type_id='.$supplier["$column_type_id"]
            .'&contact='.$supplier["$column_contact"]
            .'&email='.$supplier["$column_email"]
            .'&phone='.$supplier["$column_phone"]
            .'&audit_url='.$supplier["$column_audit_url"]
            .'&audit_account='.$supplier["$column_audit_account"]
            .'&audit_pwd='.$supplier["$column_audit_pwd"]
            .'&notes='.$supplier["$column_notes"]
            .'">修改</a></div>';
?>
								</td>
								<td>
<?php 
    echo  '<div class="column_value_center"><a href="supplier_remove.php'
            .'?supplier='.$supplier["$column_supplier"]
            .'&type='.$array_supplier_types[$supplier["$column_type_id"]]
            .'&contact='.$supplier["$column_contact"]
            .'&email='.$supplier["$column_email"]
            .'&phone='.$supplier["$column_phone"]
            .'&audit_url='.$supplier["$column_audit_url"]
            .'&audit_account='.$supplier["$column_audit_account"]
            .'&audit_pwd='.$supplier["$column_audit_pwd"]
            .'&notes='.$supplier["$column_notes"]
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
