<?php 
require_once 'check_login.php';
$roles_required = Array(
    2     //管理员
);
require_once 'role_check.php';

$page_title = "系统帐号管理！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "account_add.php";
$link_name = "添加帐号";
require_once '../res/righttop_link.php';

$page_fetch_limit = 10;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
$column_role_id = DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID;
$column_customer_serial = DatabaseProxy::DB_COLUMN_ACCOUNT_CUSTOMER_SERIAL;
$column_email = DatabaseProxy::DB_COLUMN_ACCOUNT_EMAIL;
$column_register_date = DatabaseProxy::DB_COLUMN_ACCOUNT_REGISTER_DATE;
$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
$role_id_customer = DatabaseProxy::DB_VALUE_ROLE_ID_CUSTOMER;
$role_id_customer_statistics = DatabaseProxy::DB_VALUE_ROLE_ID_CUSTOMER_STATISTICS;


$session_name = 'account';
$default_sort = $column_role_id;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';

?>
					<div align="center">
						<table width="100%" border="3">
							<tr>
								<td width="14%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_account&order=$reorder"?>">帐号名称</a></div>
								</td>
								<td width="14%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_role_id&order=$reorder"?>">角色类型</a></div>
								</td >
								<td width="16%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_email&order=$reorder"?>">电子邮箱</a></div>
								</td>
								<td width="14%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_register_date&order=$reorder"?>">注册日期</a></div>
								</td>
								<td width="16%">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_customer_serial&order=$reorder"?>">关联客户</a></div>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>

<?php


$limit = $page_fetch_limit;
require_once '../utilities/class.accountmanager.php';
$mgr = new AccountManager();
$total = $mgr->getTotal();
$num_pages = ceil($total/$limit);
$array_accounts = $mgr->fetchAccounts($sort, $cur_page, $limit, $order);
$array_types = DatabaseProxy::_DB_VALUE_ACCOUNT_ROLE_NAME();
foreach ($array_accounts as $account){
?>

							<tr>
								<td >
									<div class="column_value"><?php echo $account["$column_account"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $array_types[$account["$column_role_id"]]?></div>
								</td >
								<td >
									<div class="column_value"><?php echo $account["$column_email"]?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $account["$column_register_date"]?></div>
								</td>
								<td>
<?php 

    if ($account["$column_role_id"] == $role_id_customer ||
     $account["$column_role_id"] == $role_id_customer_statistics){
        echo  '<div class="column_value_center">'.$account["$column_customer"].'（<a href="account_grant.php'
                .'?account='.$account["$column_account"]
                .'&customer='.$account["$column_customer"]
                .'">变更授权</a>）</div>';
    }
    else {
        echo  '<div class="column_value_center">'.$account["$column_customer"].'</div>';
    }
    
?>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="account_edit.php'
            .'?account='.$account["$column_account"]
            .'&email='.$account["$column_email"]
            .'&role_id='.$account["$column_role_id"]
            .'">修改信息</a></div>';
?>
								</td>
								<td>
<?php 
    echo  '<div class="column_value_center"><a href="account_pwd_reset.php'
            .'?account='.$account["$column_account"]
            .'&email='.$account["$column_email"]
            .'&role='.$array_types[$account["$column_role_id"]]
            .'&customer='.$account["$column_customer"]
            .'">密码重置</a></div>';
?>
								</td>
								<td>
<?php 
    echo  '<div class="column_value_center"><a href="account_remove.php'
            .'?account='.$account["$column_account"]
            .'&email='.$account["$column_email"]
            .'&role='.$array_types[$account["$column_role_id"]]
            .'&customer='.$account["$column_customer"]
            .'">删除</a></div>';
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
						<?php echo $page_links?>
					</div>
<?php 
require_once '../res/footer.php';
?>
