<?php 
$page_title = "授权用户管理系统"; 
require_once '../admin/check_login.php';
require_once '../res/header.php';
require_once '../res/navigator_customer.php';

require_once '../res/righttop_link.php';
require_once '../utilities/class.accountmanager.php';
$mgr = new AccountManager();
$customer = $mgr->getAccountCustomer($_SESSION['account']);


if($_SESSION['role_id']==7){
	echo "<meta http-equiv='refresh' content='5;url=customer_links.php' />";
}
else{
	echo "<meta http-equiv='refresh' content='5;url=report_links.php' />";
}

?>


<div align="center">
	<table width="100%" border="">
		<tr>
			<td>
					<div align="center">
						<table border="3">
							<tr>
								<td>
									<div align="left">
										<h3><?php echo $customer?>的帐号"<?php echo $_SESSION['account']?>"访问记录如下：</h3>
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
