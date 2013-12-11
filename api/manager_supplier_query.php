<?php
/**
 * API: '../api/manager_supplier_query.php'
 * 
 * @return JSON
 *         [{"supplier_serial":serial,"supplier":"name"}]
 */
require_once '../utilities/class.suppliermanager.php';
$mgr = new SupplierManager();
$array_list = $mgr->getSuppliers();

require_once 'api_constants.php';
if(count($array_list)>0){
    $resp = TRUE;
} 
else {
    $resp = FALSE;
}
echo json_encode(Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_RESP_ARRAY => $array_list));

?>