<?php $navi_width = "100%"; ?>
<hr />
<div align="center">
	<table width="<?php echo $navi_width?>" border="0">
		<tr>
			<td width="33%">
				<div align="left">
					<a href="../admin/change_pwd.php">密码修改</a>
				</div>
			</td>
			
			<td width="34%">
			
				<div align="center">
					<?php 
					if($_SESSION['role_id']==7){
						echo '<a href="../customer/customer_links.php">系统管理</a>';
					}
					else{
						echo '<a href="../customer/report_links.php">数据报告</a>';
					}
					?>
					
				</div>
			</td>
			<td width="33%">
				<div align="right">
					<a href="../admin/log_out.php">系统登出</a>
				</div>
			</td>
		</tr>
	</table>
</div>
<hr />
