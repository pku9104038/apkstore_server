<?php
/**
 * API: '../api/manager_category_query.php'
 * 
 * @return JSON
 *         [{"category_serial":serial,"category":"name"}]
 */

require_once '../utilities/class.categorymanager.php';
$mgr = new CategoryManager();
$array_list = $mgr->getCategories();

require_once 'api_constants.php';
if(count($array_list)>0){
    $resp = TRUE;
} 
else {
    $resp = FALSE;
}
echo json_encode(Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_RESP_ARRAY => $array_list));

?>