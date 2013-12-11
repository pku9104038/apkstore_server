<?php
/**
 * API: '../api/manager_brand_query.php'
 * 
 * @return JSON
 *         [{"brand_serial":serial,"brand":"name"}]
 */
require_once '../api/api_constants.php';
$customer_serial = $_REQUEST[API_CONSTANTS::API_PARAM_CUSTOMER_SERIAL];

require_once '../utilities/class.brandmanager.php';
$mgr = new BrandManager();
$array_list = $mgr->getBrandsByCustomer($customer_serial);

if(count($array_list)>0){
    $resp = TRUE;
} 
else {
    $resp = FALSE;
}
echo json_encode(Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_RESP_ARRAY => $array_list));

?>