<?php

if (isset ( $_POST ['select'] )){
	$_SESSION["period_selected"] = $_POST['period_select']+0;
}


if (empty($_SESSION["period_selected"])){
	$period_selected = 4;
}
else{
	$period_selected = $_SESSION["period_selected"];
}

require_once '../utilities/class.utilities.php';
$util = new Utilities();
$period_array = $util->getPeriodArray($period_selected, $date_start, $date_end);

?>
<td>
	<div align="left">
		按
		<select name="period_select" >
			<option value="4" 
				<?php 
				if($period_selected==4){
					echo 'selected="selected"';
				}
				?>
			>日</option>

			<option value="3"
				<?php 
				if($period_selected==3){
					echo 'selected="selected"';
				}
				?>
			>周</option>
	
			<option value="2"
				<?php 
				if($period_selected==2){
					echo 'selected="selected"';
				}
				?>
			>月</option>
	
			<option value="1"
				<?php 
				if($period_selected==1){
					echo 'selected="selected"';
				}
				?>
			>年</option>
		</select>
		统计
	</div>
</td>
		
