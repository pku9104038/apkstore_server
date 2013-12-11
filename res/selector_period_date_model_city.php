<div align="center">
<form name="form_selector" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
	<table width="80%">
		<tr>
		
			<?php 
			require '../res/selector_date.php';
			require '../res/selector_model.php';
			require '../res/selector_city.php';
			?>
			
		</tr>
		
		<tr>
			<?php 
			require '../res/selector_period.php'
			?>
			<td>
				<div align="left">
					<input name="select" type="submit" id="select" value="查询" />
				</div>
			</td>
			
		
		</tr>
	
	
	</table>
</form>
</div>

