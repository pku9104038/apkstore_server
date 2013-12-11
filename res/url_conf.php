<?php 

    $xml = simplexml_load_file('../conf/url_conf.xml');
    $json = json_encode($xml);
    $obj = json_decode($json);

    $path_root = $obj->URL_HOST.$obj->URL_PORT.$obj->URL_ROOT;
     
//    $path_upload  = $path_root.$obj->PATH_UPLOAD;  
//    $path_download = $path_root.$obj->PATH_DOWNLOAD;
    $path_apkicons = $path_root.$obj->PATH_APKICONS;
    $path_groupicons = $path_root.$obj->PATH_GROUPICONS;
    
    
    //use api_constants replace url_conf
    require_once '../api/api_constants.php';
    $path_apkicons = API_CONSTANTS::PATH_ICON;
    $path_groupicons = API_CONSTANTS::PATH_GUI;
    
?>