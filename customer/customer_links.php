<?php 
require_once '../admin/check_login.php';
$roles_required = Array(
    2,    //管理员
    3,    //客户信息管理员
    4,     //应用仓库管理员
    7
);
require_once '../admin/role_check.php';

$page_title = "系统管理！"; 
require_once '../res/header.php';
require_once '../res/navigator_customer.php';
require_once '../res/righttop_link.php';
?>

					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="33%"><h4 align="center">产品管理</h4></td>
								<td width="34%"><h4 align="center">应用管理</h4></td>
								<td width="33%"><h4 align="center">应用过滤</h4></td>
							</tr>
							
							<tr>
								<td>
									<div align="left"><a href="../customer/customer_model_manager.php?page=1">机型管理</a></div>
								</td>
								<td>
									<div align="left"><a href="../customer/customer_group_manager.php?page=1">定制分组</a></div>
								</td>
								<td>
									<div align="left"><a href="../customer/customer_blacklist_manager?page=1">黑名单</a></div>
								</td>
							</tr>
							
						</table>
					</div>
			


<?php 
require_once '../res/footer.php';
?>
