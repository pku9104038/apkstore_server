<?php 
$page_title = "管理与查询系统！"; 
require_once './check_login.php';
require_once '../res/header.php';
require_once '../res/navigator.php';

require_once '../res/righttop_link.php';
?>

<meta http-equiv='refresh' content='5;url=admin_links.php' />
<div align="center">
	<table width="100%" border="0">
		<tr>
			<td>
					<div align="center">
						<table border="3">
							<tr>
								<td>
									<div align="left">
										<h3>您的帐号访问记录如下：</h3>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><label>这是您第<?php echo $_SESSION['login_times']; ?>次登录系统！</label></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><label>您上次登录系统的时间是：<?php echo $_SESSION['login_latest']; ?></label></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><label><?php echo $_SERVER['SERVER_NAME']; ?>主机IP：<?php echo gethostbyname($_SERVER['SERVER_NAME']); ?></label></div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="left"><label>访问者IP：<?php echo $_SERVER['REMOTE_ADDR']; ?></label></div>
								</td>
							</tr>
							</table>
					</div>
			
			</td>
		</tr>
	</table>
</div>

<?php 
require_once '../res/footer.php';
?>
