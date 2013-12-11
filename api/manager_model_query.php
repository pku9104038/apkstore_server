<?php
/**
 * API: '../api/manager_brand_query.php'
 * 
 * @return JSON
 *         [{"brand_serial":serial,"brand":"name"}]
 */
require_once '../api/api_constants.php';
$brand_array[0] = $_REQUEST[API_CONSTANTS::API_PARAM_BRAND_SERIAL];

require_once '../utilities/class.modelmanager.php';
$mgr = new ModelManager();
$array_list = $mgr->getModelByBrand($brand_array);

if(count($array_list)>0){
    $resp = TRUE;
} 
else {
    $resp = FALSE;
}
echo json_encode(Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_RESP_ARRAY => $array_list));

?>