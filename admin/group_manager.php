<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "应用分组基本信息管理"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "group_add.php";
$link_name = "添加新分组";
require_once '../res/righttop_link.php';

$page_fetch_limit = 20;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_group = DatabaseProxy::DB_COLUMN_GROUP;
$column_group_serial = DatabaseProxy::DB_COLUMN_GROUP_SERIAL;
$column_icon = DatabaseProxy::DB_COLUMN_GROUP_ICON;
$column_priority = DatabaseProxy::DB_COLUMN_GROUP_PRIORITY;
$column_notes = DatabaseProxy::DB_COLUMN_GROUP_NOTES;
$column_register_date = DatabaseProxy::DB_COLUMN_GROUP_REGISTER_DATE;

$session_name = 'group';
$default_sort = $column_priority;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';

?>


					<div align="center">
						<table  border="3">
							<tr>
								<td width="48">
									<div class="column_name">图标</div>
								</td >
								<td width="96" >
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_group&order=$reorder"; ?>">分组名称</a></div>
								</td>
								<td  width="96">
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_priority&order=$reorder"; ?>">优先级</a></div>
								</td>
								<td width="50%">
									<div class="column_name">包含门类</div>
								</td>
								<td  width="48"></td>
							</tr>

<?php
require_once '../utilities/class.groupmanager.php';
require_once '../api/api_constants.php';
$mgr = new GroupManager();
$path_groupicons = API_CONSTANTS::PATH_GUI;

$limit = $page_fetch_limit;
$total = $mgr->getTotal();
$num_pages = ceil($total/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}
$array_groups = $mgr->fetchGroups($sort, $cur_page, $limit, $order);

require_once '../res/url_conf.php';
foreach ($array_groups as $group){
?>

							<tr>
								<td width="48" >
									<img src="<?php echo $path_groupicons.$group["$column_icon"]?>" width="48" height="48" alt="<?php echo $path_groupicons.$group["$column_icon"]?>">
								</td>
								<td  >
									<div class="column_value_center"><a href="<?php echo "application_manager_group.php?group_serial=".$group["$column_group_serial"]?>"><?php echo $group["$column_group"]?></a></div>
								</td>
								<td>
									<div class="column_value_center"><?php echo $group["$column_priority"]?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $group["$column_notes"]?></div>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="group_edit.php'
            .'?group='.$group["$column_group"]
            .'&group_serial='.$group["$column_group_serial"]
            .'&priority='.$group["$column_priority"]
            .'&icon='.$group["$column_icon"]
            .'&notes='.$group["$column_notes"]
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
