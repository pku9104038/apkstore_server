<?php

require_once '../proxy/class.databaseproxy.php';

$column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
$column_brand = DatabaseProxy::DB_COLUMN_BRAND;

$column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
$column_model = DatabaseProxy::DB_COLUMN_MODEL;

$column_province_serial = DatabaseProxy::DB_COLUMN_PROVINCE_SERIAL;
$column_province = DatabaseProxy::DB_COLUMN_PROVINCE;

$column_city_serial = DatabaseProxy::DB_COLUMN_CITY_SERIAL;
$column_city = DatabaseProxy::DB_COLUMN_CITY;

require_once '../utilities/class.brandmanager.php';
$mgr = new BrandManager();
$brands = $mgr->getBrands();

require_once '../utilities/class.modelmanager.php';
$modelManager = new ModelManager();
$models = $modelManager->getModelByBrand(0);
$model_names = $modelManager->getModelNames();


require_once '../utilities/class.provincemanager.php';
$provinceMgr = new ProvinceManager();
$provinces = $provinceMgr->getProvinces();

require_once '../utilities/class.citymanager.php';
$cityMgr = new CityManager();
$cities = $cityMgr->getCities();

$today = date("Y-m-d");
if (empty($_SESSION["date_start"])){
	$date_start = $today;
}
else{
	$date_start = $_SESSION["date_start"];
}
if (empty($_SESSION["date_end"])){
	$date_end = $today;
}
else{
	$date_end = $_SESSION["date_end"];
}

if (empty($_SESSION["date_end"])){
	$date_end = date("Y-m-d");
}
else{
	$date_end = $_SESSION["date_end"];
}

if (empty($_SESSION["brand_selected"])){
	$brand_selected = 0;
}
else{
	$brand_selected = $_SESSION["brand_selected"];
}

if (empty($_SESSION["brand_selected_serial"])){
	$brand_selected_serial = 0;
}
else{
	$brand_selected_serial = $_SESSION["brand_selected_serial"];
}

if (empty($_SESSION["model_selected"])){
	$model_selected = 0;
}
else{
	$model_selected = $_SESSION["model_selected"];
}

if (empty($_SESSION["model_selected_serial"])){
	$model_selected_serial = 0;
}
else{
	$model_selected_serial = $_SESSION["model_selected_serial"];
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

if (empty($_SESSION["period_selected"])){
	$period_selected = 1;
}
else{
	$period_selected = $_SESSION["period_selected"];
}


if (isset ( $_POST ['query'] )){
	
	$_SESSION["date_start"] = $_POST['date_start'];
	$_SESSION["date_end"] = $_POST['date_end'];
	$_SESSION["brand_selected"] = $_POST['brand_select']+0;
	$_SESSION["model_selected"] = $_POST['model_select']+0;
	$_SESSION["province_selected"] = $_POST['province_select']+0;
	$_SESSION["city_selected"] = $_POST['city_select']+0;
	$_SESSION["period_selected"] = $_POST['period_select']+0;
	
	
	$date_start = $_SESSION["date_start"];
	$date_end = $_SESSION["date_end"];	
	
	
	$brand_selected = $_SESSION["brand_selected"];
	$model_selected = $_SESSION["model_selected"];
	$province_selected = $_SESSION["province_selected"];
	$city_selected = $_SESSION["city_selected"];
	$period_selected = $_SESSION["period_selected"];
	
	

}	
	if (strtotime($date_end)>strtotime($today)) {
		$date_end = $today;
	}
	if (strtotime($date_start)>strtotime($date_end)) {
		$date_start = $date_end;
	}
	
	require_once '../utilities/class.utilities.php';
	$util = new Utilities();
	$period_array = $util->getPeriodArray($period_selected, $date_start, $date_end);

	if($brand_selected>0){
		$brand_selected_serial = $brands[$brand_selected-1]["$column_brand_serial"];
	}
	else{
		$brand_selected_serial = 0;
	}
	
	if($model_selected>0){
		$model_selected_serial = $models[$model_selected-1]["$column_model_serial"];
	}
	else{
		$model_selected_serial = 0;
	}
	
	$_SESSION["brand_selected_serial"] = $brand_selected_serial;
	
	$_SESSION["model_selected_serial"] = $model_selected_serial;
	
	$model_array = FALSE;
	if($model_selected_serial==0){
		if($brand_selected_serial>0){
			$i=0;
			for($j=0; $j<count($models);$j++){
				if ($models[$j]["$column_brand_serial"]+0==$brand_selected_serial) {
					$model_array[$i] = $models[$j]["$column_model_serial"];
					$i++;
				}
			}
		}
	}
	else{
		$model_array[0] = $model_selected_serial;
	}
	
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
			for($j=0; $j<count($provinces);$j++){
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
	

?>

<script type="text/javascript" src="../res/calendar.js"></script>

<form name="form_date_model" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">

	<table>
		<tr>
		<td>
			<div align="left">
				按
				<select name="period_select" >
					<option value="3" 
						<?php 
						if($period_selected==3){
							echo 'selected="selected"';
						}
						?>
					>日</option>
					<option value="2"
						<?php 
						if($period_selected==2){
							echo 'selected="selected"';
						}
						?>
					>周</option>
					<option value="1"
						<?php 
						if($period_selected==1){
							echo 'selected="selected"';
						}
						?>
					>月</option>
					<option value="0"
						<?php 
						if($period_selected==0){
							echo 'selected="selected"';
						}
						?>
					>年</option>
				</select>
				统计
			</div>
		</td>
		
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
		
		<td>
			<div align="left">	
				品牌
				<select name="brand_select" onChange="resetModel(this.selectedIndex)"></select>
			</div>
		</td>
		<td>
			<div align="left">
				机型
				<select name="model_select"></select>
			</div>
		</td>

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
		
		<td>
			<div align="left">
				<input name="query" type="submit" id="query" value="查询" />
			</div>
		</td>
		</tr>
	</table>

</form>

<?php 
echo '<script language="javascript" type="text/javascript">';

echo 'var brand_selectArr = new Array();';
echo 'var model_selectArr = new Array();';
echo 'brand_selectArr[0] = ["全部品牌","0","0"];';
echo 'model_selectArr[0] = ["全部机型","0","0","0"];';
for($i=0;$i<count($brands);$i++){
	echo 'brand_selectArr['.($i+1).'] = ["'.$brands[$i]["$column_brand"].'","'.$brands[$i]["$column_brand_serial"].'","'.($i+1).'"];';
	echo 'model_selectArr['.($i+1).'] = ["全部机型","0","'.$brands[$i]["$column_brand_serial"].'","0"];';
}

for($j=0;$j<count($models);$j++){
	echo 'model_selectArr['.($i+1+$j).'] = ["'.$models[$j]["$column_model"].'","'.$models[$j]["$column_model_serial"].'","'.$models[$j]["$column_brand_serial"].'","'.(1+$j).'"];';
}

echo 'var brand_selected ='.$brand_selected.';';
echo 'var model_selected ='.$model_selected.';';

echo '</script>';

//echo "brand_selected:".$brand_selected;
//echo "model_selected:".$model_selected;
//echo "brand_serial:".$brand_selected_serial;
//echo "model_serial:".$model_selected_serial;
			
?>

<script language="javascript" type="text/javascript">	 

function resetModel(brand)	 
{	 
	
	for (var i=document.form_date_model.model_select.length-1;i>-1;i--)	 
	{	 
		document.form_date_model.model_select.remove(i);	 
	}	 
	
	var arr = model_selectArr;
	var j=0;	 
	for (var i=0;i<arr.length;i++)	 
	{	 
		if(arr[i][2]==brand_selectArr[brand][1]){
			document.form_date_model.model_select.options[j] = new Option(arr[i][0],arr[i][3]);
			if(arr[i][3]==model_selected){
				document.form_date_model.model_select.options[j].selected = true;	
			}
			j++;
		}	 
	}	 
}	 
for (var i=0;i<brand_selectArr.length;i++)	 
{ 
	document.form_date_model.brand_select.options[i] = new Option(brand_selectArr[i][0],brand_selectArr[i][2]);
	if(brand_selectArr[i][2]==brand_selected){
		document.form_date_model.brand_select.options[i].selected = true; 
	}	 
} 
resetModel(brand_selected);	 
</script>


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

//echo "province_selected:".$province_selected;
//echo "city_selected:".$city_selected;
//echo "brand_serial:".$province_selected_serial;
//echo "model_serial:".$city_selected_serial;
			
?>

<script language="javascript" type="text/javascript">	 

function resetCity(province)	 
{	 
	
	for (var i=document.form_date_model.city_select.length-1;i>-1;i--)	 
	{	 
		document.form_date_model.city_select.remove(i);	 
	}	 
	
	var arr = city_selectArr;
	var j=0;	 
	for (var i=0;i<arr.length;i++)	 
	{	 
		if(arr[i][2]==province_selectArr[province][1]){
			document.form_date_model.city_select.options[j] = new Option(arr[i][0],arr[i][3]);
			if(arr[i][3]==city_selected){
				document.form_date_model.city_select.options[j].selected = true;	
			}
			j++;
		}	 
	}	 
}	 
for (var i=0;i<province_selectArr.length;i++)	 
{ 
	document.form_date_model.province_select.options[i] = new Option(province_selectArr[i][0],province_selectArr[i][2]);
	if(province_selectArr[i][2]==province_selected){
		document.form_date_model.province_select.options[i].selected = true; 
	}	 
} 
resetCity(province_selected);	 
</script>

<?php 
//echo json_encode($period_array);
?>
