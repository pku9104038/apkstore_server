<?php
/**
 * API: '../api/manager_category_group_query.php'
 * 
 * @return JSONArray
 *         [{"group_serial":serial,"group_name":"name",
 *             "categories":
 *             [{"category_serial":category_serial,"category":"categry"}]}]
 *          }]
 */
require_once '../utilities/class.groupmanager.php';
$groupMgr = new GroupManager();
$array_group = $groupMgr->getGroupsAll();

require_once '../utilities/class.categorymanager.php';
$mgr = new CategoryManager();
$i=0;
foreach ($array_group as $group){
    $array_list[$i]['group_serial'] = $group['group_serial'];
    $array_list[$i]['group_name'] = $group['group_name'];
    $array_list[$i]['categories'] = $mgr->getCategoriesByGroup($group['group_serial']);
    $i++;
}

require_once 'api_constants.php';
if(count($array_list)>0){
    $resp = TRUE;
} 
else {
    $resp = FALSE;
}
echo json_encode(Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_RESP_ARRAY => $array_list));

?>