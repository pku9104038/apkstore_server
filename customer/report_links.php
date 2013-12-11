<?php 
require_once '../admin/check_login.php';
$roles_required = Array(
    2,    //管理员
    5,    //统计信息管理员
    6,    //信息统计
    9     //客户操作员
);
require_once '../admin/role_check.php';

$page_title = "统计数据报告"; 
require_once '../res/header.php';
require_once '../res/navigator_customer.php';
?>


					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="50%"><h4 align="center">终端统计</h4></td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../customer/report_imei.php">终端IMEI查询</a></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../customer/report_device_new.php">终端注册查询</a></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../customer/report_device_statistics.php">终端时段统计</a></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../customer/report_device_cities.php">终端地域统计</a></div>
								
								</td>
							</tr>
							</table>
					</div>
			


<?php 
require_once '../res/footer.php';
?>
