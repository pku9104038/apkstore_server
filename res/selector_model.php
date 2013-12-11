<?php

if (isset ( $_POST ['select'] )){

	$_SESSION["brand_selected"] = $_POST['brand_select']+0;
	$_SESSION["model_selected"] = $_POST['model_select']+0;


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
require_once '../utilities/class.accountmanager.php';
$accountMgr = new AccountManager();
$customer_serial = $accountMgr->getAccountCustomerSerial($_SESSION['account']);
$brand_array = $accountMgr->getBrandSerialsByCustomer($_SESSION['account']);

require_once '../proxy/class.databaseproxy.php';
$column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
$column_brand = DatabaseProxy::DB_COLUMN_BRAND;

$column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
$column_model = DatabaseProxy::DB_COLUMN_MODEL;

require_once '../utilities/class.brandmanager.php';
$mgr = new BrandManager();
if($customer_serial>0){
	$brands = $mgr->getBrandsByCustomer($customer_serial);
}
else {
	$brands = $mgr->getBrands();
}

require_once '../utilities/class.modelmanager.php';
$modelManager = new ModelManager();
$models = $modelManager->getModelByBrand($brand_array);
$model_names = $modelManager->getModelNames();


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
	else{
		if($customer_serial>0){
			
			for($j=0; $j<count($models);$j++){
				$model_array[$j] = $models[$j]["$column_model_serial"];
			}
		}
	}
}
else{
	$model_array[0] = $model_selected_serial;
}
//echo " brand_array:".json_encode($brand_array);
//echo " customer_serial:".$customer_serial;
//echo " brands:".json_encode($brands);
//echo " models:".json_encode($models);
//echo " model_names:".json_encode($model_names);

//echo " model_array:".json_encode($model_array);	
?>

		
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
	
	for (var i=document.form_selector.model_select.length-1;i>-1;i--)	 
	{	 
		document.form_selector.model_select.remove(i);	 
	}	 
	
	var arr = model_selectArr;
	var j=0;	 
	for (var i=0;i<arr.length;i++)	 
	{	 
		if(arr[i][2]==brand_selectArr[brand][1]){
			document.form_selector.model_select.options[j] = new Option(arr[i][0],arr[i][3]);
			if(arr[i][3]==model_selected){
				document.form_selector.model_select.options[j].selected = true;	
			}
			j++;
		}	 
	}	 
}	 
for (var i=0;i<brand_selectArr.length;i++)	 
{ 
	document.form_selector.brand_select.options[i] = new Option(brand_selectArr[i][0],brand_selectArr[i][2]);
	if(brand_selectArr[i][2]==brand_selected){
		document.form_selector.brand_select.options[i].selected = true; 
	}	 
} 
resetModel(brand_selected);	 
</script>

