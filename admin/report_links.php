<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    5,    //统计信息管理员
    6     //信息统计
);
require_once 'role_check.php';

$page_title = "统计数据报告"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
?>


					<div align="center">
						<table width="80%" border="3">
							<tr>
								<td width="33%"><h4 align="center">终端统计</h4></td>
								<td width="34%"><h4 align="center">下载统计</h4></td>
								<td width="33%"><h4 align="center">运行统计</h4></td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="report_device_new.php">终端注册查询</a></div>
								</td>
								<td>
									<div align="left"><a href="report_download_app.php">应用下载统计</a></div>
								</td>
								<td>
									<div align="left"><a href="supplier_manager.php?page=1">常规使用排行榜</a></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="report_device_statistics.php">终端时段统计</a></div>
								</td>
								<td>
									<div align="left"><a href="../admin/report_download_period.php">时段下载统计</a></div>
								</td>
								<td>
									<div align="left"><a href="group_manager.php?page=1">应用使用排行榜</a></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../admin/report_device_cities.php">终端地域统计</a></div>
								
								</td>
								<td>
									<div align="left"><a href="report_install_list">安装排行榜</a></div>
								</td>
								<td>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../admin/report_device_online.php">在线终端统计</a></div>
								</td>
								<td>
									<div align="left"><a href="report_remove_list">卸载排行榜</a></div>
								</td>
								<td>
								
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../admin/report_device_active_app.php">活跃应用统计</a></div>
								</td>
								<td>
							
								</td>
								<td>
								
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../admin/report_device_active_widget.php">活跃桌面统计</a></div>
								</td>
								<td>
							
								</td>
								<td>
								
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><a href="../admin/report_device_active.php">活跃用户统计</a></div>
								</td>
								<td>
							
								</td>
								<td>
								
								</td>
							</tr>
							</table>
					</div>
			


<?php 
require_once '../res/footer.php';
?>
