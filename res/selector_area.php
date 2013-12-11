<?php

if (isset ( $_POST ['select'] )){
	$_SESSION["area_selected"] = $_POST['area_select']+0;
}


if (empty($_SESSION["area_selected"])){
	$area_selected = 1;
}
else{
	$area_selected = $_SESSION["area_selected"];
}

require_once '../utilities/class.utilities.php';
$util = new Utilities();
$area_level = $area_selected;
$area_array = $util->getAreaArray($area_level, $province_selected_serial,$city_selected_serial);

?>
<td>
	<div align="left">
		按
		<select name="area_select" >
			<option value="2"
				<?php 
				if($area_selected==2){
					echo 'selected="selected"';
				}
				?>
			>城市</option>
	
			<option value="1"
				<?php 
				if($area_selected==1){
					echo 'selected="selected"';
				}
				?>
			>省区</option>
		</select>
		统计
	</div>
</td>
		
