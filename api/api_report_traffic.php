<?php
require_once '../utilities/class.trafficmanager.php';
require_once '../api/api_constants.php';
require_once '../proxy/class.databaseproxy.php';
$apiname = basename($_SERVER['SCRIPT_NAME'],".php");
$resp = FALSE;

$devsn = $_REQUEST[API_CONSTANTS::API_PARAM_DEV_SN]+0;

if($devsn > 0){
	$date = $_REQUEST[API_CONSTANTS::API_PARAM_REPORT_DATE];
	$my_rx = $_REQUEST[DatabaseProxy::DB_COLUMN_TRAFFIC_MY_RX]+0;
	$my_tx = $_REQUEST[DatabaseProxy::DB_COLUMN_TRAFFIC_MY_TX]+0;
	$mobile_rx = $_REQUEST[DatabaseProxy::DB_COLUMN_TRAFFIC_MOBILE_RX]+0;
	$mobile_tx = $_REQUEST[DatabaseProxy::DB_COLUMN_TRAFFIC_MOBILE_TX]+0;
	$total_rx = $_REQUEST[DatabaseProxy::DB_COLUMN_TRAFFIC_TOTAL_RX]+0;
	$total_tx = $_REQUEST[DatabaseProxy::DB_COLUMN_TRAFFIC_TOTAL_TX]+0;
	$mgr = new TrafficManager();
	if($mgr->isNewRecord($devsn, $date)){
		if($mgr->addRecord($devsn, $date, $my_rx, $my_tx, $mobile_rx, $mobile_tx, $total_rx, $total_tx)){
			$resp = TRUE;
		}
	}
	else{
		$resp = TRUE;
	}
}
$array = Array(API_CONSTANTS::API=>$apiname,
		API_CONSTANTS::API_RESP => $resp);

echo json_encode($array);

?>