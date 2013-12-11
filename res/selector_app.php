<?php

if (isset ( $_POST ['select'] )){

	$_SESSION["group_selected"] = $_POST['group_select']+0;
	$_SESSION["app_selected"] = $_POST['app_select']+0;
	

}

if (empty($_SESSION["group_selected"])){
	$group_selected = 0;
}
else{
	$group_selected = $_SESSION["group_selected"];
}

if (empty($_SESSION["group_selected_serial"])){
	$group_selected_serial = 0;
}
else{
	$group_selected_serial = $_SESSION["group_selected_serial"];
}


if (empty($_SESSION["app_selected"])){
	$app_selected = 0;
}
else{
	$app_selected = $_SESSION["app_selected"];
}

if (empty($_SESSION["app_selected_serial"])){
	$app_selected_serial = 0;
}
else{
	$app_selected_serial = $_SESSION["app_selected_serial"];
}

require_once '../utilities/class.accountmanager.php';
$accountMgr = new AccountManager();
$customer_serial = $accountMgr->getAccountCustomerSerial($_SESSION['account']);

require_once '../proxy/class.databaseproxy.php';
$column_group_serial = DatabaseProxy::DB_COLUMN_GROUP_SERIAL;
$column_group = DatabaseProxy::DB_COLUMN_GROUP;

$column_category_serial = DatabaseProxy::DB_COLUMN_CATEGORY_SERIAL;
$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;

$column_application = DatabaseProxy::DB_COLUMN_APPLICATION;
$column_application_serial = DatabaseProxy::DB_COLUMN_APPLICATION_SERIAL;
$column_appl_package = DatabaseProxy::DB_COLUMN_APPLICATION_PACKAGE;

require_once '../utilities/class.groupmanager.php';
$mgr = new GroupManager();
if($customer_serial>0){
//	$groups = $mgr->getGroupsByCustomer($customer_serial);
	$groups = $mgr->getGroups();
}
else {
	$groups = $mgr->getGroups();
}
//$group_array = $mgr->getGroupSerialsByCustomer($_SESSION['account']);

$group_array = $mgr->getGroupsSerialArray();
if($group_selected>0){
	$group_selected_serial = $groups[$group_selected-1]["$column_group_serial"];
}
else{
	$group_selected_serial = 0;
}
$group_selected_serial = $group_selected;

$_SESSION["group_selected_serial"] = $group_selected_serial;




require_once '../utilities/class.applicationmanager.php';
$appMgr = new ApplicationManager();
$applications = $appMgr->getApplicationsByCustomer();

if($app_selected>0){
	$app_selected_serial = $applications[$app_selected-1]["$column_application_serial"];
}
else{
	$app_selected_serial = 0;
}
$app_selected_serial = $app_selected;
$_SESSION["app_selected_serial"] = $app_selected_serial;

$app_array = FALSE;
if($app_selected_serial==0){
	if($group_selected_serial>0){
		$i=0;
		for($j=0; $j<count($applications);$j++){
			if ($applications[$j]["$column_group_serial"]+0==$group_selected_serial) {
				$app_array[$i] = $applications[$j]["$column_appl_package"];
				$i++;
			}
		}
	}
	else{
			for($j=0; $j<count($applications);$j++){
				$app_array[$j] = $applications[$j]["$column_appl_package"];
			}
				
	}
}
else{
		
		for($j=0; $j<count($applications);$j++){
			if ($applications[$j]["$column_application_serial"]+0==$app_selected_serial) {
				$app_array[0] = $applications[$j]["$column_appl_package"];
				break;
			}
		}
	//$app_array[0] = $app_selected_serial;
}

?>

		
<td>
	<div align="left">	
		分组
		<select name="group_select" onChange="resetApp(this.selectedIndex)"></select>
	</div>
</td>
<td>
</td>	
<td>
	<div align="left">	
		应用
		<select name="app_select" ></select>
	</div>
	
</td>

<?php 
echo '<script language="javascript" type="text/javascript">';

echo 'var group_selectArr = new Array();';
echo 'var app_selectArr = new Array();';
echo 'group_selectArr[0] = ["全部分组","0","0"];';
echo 'app_selectArr[0] = ["全部应用", "0", "0", "0"];';
for($i=0;$i<count($groups);$i++){
	echo 'group_selectArr['.($i+1).'] = ["'.$groups[$i]["$column_group"].'","'.$groups[$i]["$column_group_serial"].'","'.($i+1).'"];';
	echo 'app_selectArr['.($i+1).'] = ["全部应用","0","'.$groups[$i]["$column_group_serial"].'","0"];';
}

for($j=0;$j<count($applications);$j++){
	echo 'app_selectArr['.($i+1+$j).'] = ["'.$applications[$j]["$column_application"].'","'.$applications[$j]["$column_application_serial"].'","'.$applications[$j]["$column_group_serial"].'","'.(1+$j).'"];';
}

echo 'var group_selected ='.$group_selected.';';
echo 'var app_selected ='.$app_selected.';';

echo '</script>';

//echo "group_selected:".$group_selected;
//echo "group_serial:".$group_selected_serial;
//echo "app_selected:".$app_selected;
//echo "app_selected_serial:".$app_selected_serial;
//print_r($category_array).'<br />';
//print_r($applications).'<br />';
//print_r($app_array);
?>

<script language="javascript" type="text/javascript">	 

function resetCategory(group)	 
{	 
	
	for (var i=document.form_selector.category_select.length-1;i>-1;i--)	 
	{	 
		document.form_selector.category_select.remove(i);	 
	}	 
	
	var arr = category_selectArr;
	var j=0;	 
	for (var i=0;i<arr.length;i++)	 
	{	 
		if(arr[i][2]==group_selectArr[group][1]){
			document.form_selector.category_select.options[j] = new Option(arr[i][0],arr[i][3]);
			if(arr[i][3]==category_selected){
				document.form_selector.category_select.options[j].selected = true;	
			}
			j++;
		}	 
	}

    resetApp(0);	 
}	 


function resetApp(group)	 
{	 
	for (var i=document.form_selector.app_select.length-1;i>-1;i--)	 
	{	 
		document.form_selector.app_select.remove(i);	 
	}	 
	
	var arr = app_selectArr;
	var j=0;	 
	for (var i=0;i<arr.length;i++)	 
	{	 
		if(arr[i][2]==group_selectArr[group][1]){
			document.form_selector.app_select.options[j] = new Option(arr[i][0],arr[i][1]);
			if(arr[i][1]==app_selected){
				document.form_selector.app_select.options[j].selected = true;	
			}
			j++;
		}	 
	}
		 
}

for (var i=0;i<group_selectArr.length;i++)	 
{ 
	document.form_selector.group_select.options[i] = new Option(group_selectArr[i][0],group_selectArr[i][1]);
	if(group_selectArr[i][1]==group_selected){
		document.form_selector.group_select.options[i].selected = true; 
	}	 
} 
resetApp(group_selected);	 
</script>

