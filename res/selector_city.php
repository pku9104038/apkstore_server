<?php
if (isset ( $_POST ['select'] )){

	$_SESSION["province_selected"] = $_POST['province_select']+0;
	$_SESSION["city_selected"] = $_POST['city_select']+0;

}

if (empty($_SESSION["province_selected"])){
	$province_selected = 0;
}
else{
	$province_selected = $_SESSION["province_selected"];
}

if (empty($_SESSION["province_selected_serial"])){
	$province_selected_serial = 0;
}
else{
	$province_selected_serial = $_SESSION["province_selected_serial"];
}

if (empty($_SESSION["city_selected"])){
	$city_selected = 0;
}
else{
	$city_selected = $_SESSION["city_selected"];
}

if (empty($_SESSION["city_selected_serial"])){
	$city_selected_serial = 0;
}
else{
	$city_selected_serial = $_SESSION["city_selected_serial"];
}


require_once '../proxy/class.databaseproxy.php';
$column_province_serial = DatabaseProxy::DB_COLUMN_PROVINCE_SERIAL;
$column_province = DatabaseProxy::DB_COLUMN_PROVINCE;

$column_city_serial = DatabaseProxy::DB_COLUMN_CITY_SERIAL;
$column_city = DatabaseProxy::DB_COLUMN_CITY;

require_once '../utilities/class.provincemanager.php';
$provinceMgr = new ProvinceManager();
$provinces = $provinceMgr->getProvinces();

require_once '../utilities/class.citymanager.php';
$cityMgr = new CityManager();
$cities = $cityMgr->getCities();


	
if($province_selected>0){
	$province_selected_serial = $provinces[$province_selected-1]["$column_province_serial"];
}
else{
	$province_selected_serial = 0;
}
	
if($city_selected>0){
	$city_selected_serial = $cities[$city_selected-1]["$column_city_serial"];
}
else{
	$city_selected_serial = 0;
}
	
$_SESSION["province_selected_serial"] = $province_selected_serial;
	
$_SESSION["city_selected_serial"] = $city_selected_serial;
	
$city_array = FALSE;
if($city_selected_serial==0){
	if($province_selected_serial>0){
		$i=0;
		for($j=0; $j<count($cities);$j++){
			if ($cities[$j]["$column_province_serial"]+0==$province_selected_serial) {
				$city_array[$i] = $cities[$j]["$column_city"];
				$i++;
			}
		}
	}
}
else{
	$city_array[0] = $cities[$city_selected-1]["$column_city"];;
}
//echo "province:".$province_selected;
//echo "province_serial:".$province_selected_serial;
//echo "city:".$city_selected;
//echo "city_serial:".$city_selected_serial;	
//echo "cities:".json_encode($cities);
//echo "citie_array:".json_encode($city_array);
?>

<td>
	<div align="left">	
		省区
		<select name="province_select" onChange="resetCity(this.selectedIndex)"></select>
	</div>
</td>
<td>
	<div align="left">
		城市
		<select name="city_select"></select>
	</div>
</td>

<?php 
echo '<script language="javascript" type="text/javascript">';

echo 'var province_selectArr = new Array();';
echo 'var city_selectArr = new Array();';
echo 'province_selectArr[0] = ["全部省区","0","0"];';
echo 'city_selectArr[0] = ["全部城市","0","0","0"];';
for($i=0;$i<count($provinces);$i++){
	echo 'province_selectArr['.($i+1).'] = ["'.$provinces[$i]["$column_province"].'","'.$provinces[$i]["$column_province_serial"].'","'.($i+1).'"];';
	echo 'city_selectArr['.($i+1).'] = ["全部城市","0","'.$provinces[$i]["$column_province_serial"].'","0"];';
}

for($j=0;$j<count($cities);$j++){
	echo 'city_selectArr['.($i+1+$j).'] = ["'.$cities[$j]["$column_city"].'","'.$cities[$j]["$column_city_serial"].'","'.$cities[$j]["$column_province_serial"].'","'.(1+$j).'"];';
}

echo 'var province_selected ='.$province_selected.';';
echo 'var city_selected ='.$city_selected.';';

echo '</script>';

			
?>

<script language="javascript" type="text/javascript">	 

function resetCity(province)	 
{	 
	
	for (var i=document.form_selector.city_select.length-1;i>-1;i--)	 
	{	 
		document.form_selector.city_select.remove(i);	 
	}	 
	
	var arr = city_selectArr;
	var j=0;	 
	for (var i=0;i<arr.length;i++)	 
	{	 
		if(arr[i][2]==province_selectArr[province][1]){
			document.form_selector.city_select.options[j] = new Option(arr[i][0],arr[i][3]);
			if(arr[i][3]==city_selected){
				document.form_selector.city_select.options[j].selected = true;	
			}
			j++;
		}	 
	}	 
}	 
for (var i=0;i<province_selectArr.length;i++)	 
{ 
	document.form_selector.province_select.options[i] = new Option(province_selectArr[i][0],province_selectArr[i][2]);
	if(province_selectArr[i][2]==province_selected){
		document.form_selector.province_select.options[i].selected = true; 
	}	 
} 
resetCity(province_selected);	 
</script>

<?php 
//echo json_encode($period_array);
?>
