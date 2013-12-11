<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3     //客户信息管理员
);
require_once 'role_check.php';

$page_title = "应用类型基本信息管理"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "category_add.php";
$link_name = "添加新应用类型";
require_once '../res/righttop_link.php';

$page_fetch_limit = 20;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
$column_serial = DatabaseProxy::DB_COLUMN_CATEGORY_SERIAL;
$column_notes = DatabaseProxy::DB_COLUMN_CATEGORY_NOTES;
$column_register_date = DatabaseProxy::DB_COLUMN_CATEGORY_REGISTER_DATE;
$column_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_GROUP_SERIAL;
$column_group = DatabaseProxy::DB_COLUMN_GROUP;
$column_group_icon = DatabaseProxy::DB_COLUMN_GROUP_ICON;



$session_name = 'category';
$default_sort = $column_category;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';

?>


					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td >
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_category&order=$reorder"; ?>">应用类型</a></div>
								</td>
							<td >
									<div class="column_name">分组图标</div>
								</td>
								<td >
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_group_serial&order=$reorder"; ?>">应用分组</a></div>
								</td>
								<td >
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_register_date&order=$reorder"; ?>">登记日期</a></div>
								</td>
								<td width="35%">
									<div class="column_name">包含应用</div>
								</td>
								<td></td>
							</tr>

<?php
require_once '../res/url_conf.php';
require_once '../utilities/class.categorymanager.php';
$mgr = new CategoryManager();

$limit = $page_fetch_limit;
$total = $mgr->getTotal();
$num_pages = ceil($total/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}
$array = $mgr->fetchCategories($sort, $cur_page, $limit, $order);
foreach ($array as $row){
?>

							<tr>
								<td >
									<div class="column_name"><?php echo $row["$column_category"];?></div>
								</td>
							<td width="48" >
									<div class="column_value" ><img  src="<?php echo $path_groupicons.$row["$column_group_icon"];?>" width="48" height="48"><?php //echo $row["$column_group"];?></div>
								</td>
								<td >
									<div class="column_value" ><?php echo $row["$column_group"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_register_date"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_notes"];?></div>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="category_edit.php'
            .'?serial='.$row["$column_serial"]
            .'&category='.$row["$column_category"]
            .'&group_serial='.$row["$column_group_serial"]
            .'&notes='.$row["$column_notes"]
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
