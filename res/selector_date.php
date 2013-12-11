<?php

require_once '../proxy/class.databaseproxy.php';

if (isset ( $_POST ['select'] )){

	$_SESSION["date_start"] = $_POST['date_start'];
	$_SESSION["date_end"] = $_POST['date_end'];

//	$date_start = $_SESSION["date_start"];
//	$date_end = $_SESSION["date_end"];

}
date_default_timezone_set("Asia/Shanghai");
$today = date("Y-m-d");
$yestoday = date("Y-m-d",strtotime($today)-24*3600);
$mday = date("j",strtotime($yestoday))+0;
//echo "mday:".$mday;
if ($mday>1) {
	$mstart = date("Y-m-d",strtotime($yestoday)-($mday-1)*24*3600);
	//echo "mstart";
}
else{
	$mstart = $yestoday;
}
if (empty($_SESSION["date_start"])){
	//$date_start = $today;
	$date_start = $mstart;
}
else{
	$date_start = $_SESSION["date_start"];
}
if (empty($_SESSION["date_end"])){
	//$date_end = $today;
	$date_end = $yestoday;
}
else{
	$date_end = $_SESSION["date_end"];
}


if (strtotime($date_end)>strtotime($today)) {
	$date_end = $today;
}
if (strtotime($date_start)>strtotime($date_end)) {
	$date_start = $date_end;
}
	

?>

<script type="text/javascript" src="../res/calendar.js"></script>


<td>
	<div align="left">
		起始日期
		<input name="date_start" type="text" id="date_start" onclick="new Calendar(2012, 2025).show(this);" value="<?php echo $date_start?>" size="10" maxlength="10" readonly="readonly" />
	</div>
</td>
		
<td>
	<div align="left">
		 截止日期
		<input name="date_end" type="text" id="date_end" onclick="new Calendar(2012, 2025).show(this);" value="<?php echo $date_end?>" size="10" maxlength="10" readonly="readonly" />
	</div>
</td>
		

