<?php
/**
 * API: '../api/manager_customer_query.php'
 * 
 * @return JSON
 *         [{"customer_serial":serial,"customer":"name"}]
 */
require_once '../api/api_constants.php';

require_once '../utilities/class.customermanager.php';
$mgr = new CustomerManager();
//$array_list = $mgr->getBrandsByCustomer($customer_serial);
$array_list = $mgr->getCustomers();

if(count($array_list)>0){
    $resp = TRUE;
} 
else {
    $resp = FALSE;
}
echo json_encode(Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_RESP_ARRAY => $array_list));

?>