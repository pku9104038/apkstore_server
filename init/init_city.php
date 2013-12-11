<html>
<body>

<?php
echo "init_city";

require_once '../proxy/class.databaseproxy.php';
$column_dev_province = DatabaseProxy::DB_COLUMN_REGISTER_PROVINCE;
$column_dev_city = DatabaseProxy::DB_COLUMN_REGISTER_CITY;

require_once '../utilities/class.devicemanager.php';
$devMgr = new DeviceManager();

if(isset($_REQUEST['date_start'])){
	$date_start = $_REQUEST['date_start'];
}
else{
	$date_start = date("Y-m-d");
}
if(isset($_REQUEST['date_end'])){
	$date_end = $_REQUEST['date_end'];
}
else{
	$date_end = date("Y-m-d");
}

$devices = $devMgr->fetchDevicesByModel(FALSE, FALSE,$date_start, $date_end,1,0);
//echo "devices:".count($devices).'<br>';

require_once '../utilities/class.provincemanager.php';
$provinceMgr = new ProvinceManager();
require_once '../utilities/class.citymanager.php';
$cityMgr = new CityManager();

foreach ($devices as $device){
	$province = $device["$column_dev_province"];
//	echo "province:".$province.'<br>';
	$city = $device["$column_dev_city"];
//   	echo "city:".$city.'<br>';
	$province_serial = $provinceMgr->getProvinceSN($province);
//	echo "province_serial:".$province_serial.'<br>';
	if ($province_serial==0) {
    	$province_serial = $provinceMgr->addProvince($province);
		echo "add province:".$province.'<br>';
	}
    if ($province_serial>0) {
    	$city_serial = $cityMgr->getCitySN($city);
    	if ($city_serial==0) {
    		$city_serial = $cityMgr->addCity($city, $province_serial);
			echo "add city:".$city.'<br>';
    	}
  	
    }	
}

?>
</body>
</html>
