<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    3,    //客户信息管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "系统管理！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
require_once '../res/righttop_link.php';
?>

					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="33%"><h4 align="center">帐号管理</h4></td>
								<td width="34%"><h4 align="center">渠道管理</h4></td>
								<td width="33%"><h4 align="center">应用管理</h4></td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="account_add.php">添加帐号</a></div>
								</td>
								<td>
									<div align="left"><a href="customer_manager.php?page=1">客户基本信息管理</a></div>
								</td>
								<td>
									<div align="left"><a href="application_manager_promotion.php?page=1">应用推荐管理</a></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="account_manager.php?page=1">帐号管理</a></div>
								</td>
								<td>
									<div align="left"><a href="brand_manager.php?page=1">品牌基本信息管理</a></div>
								</td>
								<td>
									<div align="left"><a href="group_manager.php?page=1">应用分组基本信息管理</a></div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div align="left"><a href="model_manager.php?page=1">型号基本信息管理</a></div>
								</td>
								<td>
									<div align="left"><a href="category_manager.php?page=1">应用类型基本信息管理</a></div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div align="left"><a href="supplier_manager.php?page=1">供应商基本信息管理</a></div>
								</td>
							<td>
									<div align="left"><a href="application_manager.php?page=1">应用产品基本信息管理</a></div>
								</td>
							</tr>
							<tr>
								<td>
								</td>
								<td>
								</td>
								<td>
									<div align="left"><a href="apkfile_manager.php?page=1">应用文件版本信息管理</a></div>
								</td>
							</tr>
						</table>
					</div>
			


<?php 
require_once '../res/footer.php';
?>
