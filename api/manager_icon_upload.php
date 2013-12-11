<?php
ini_set('max_execution_time', '0');

require_once '../utilities/class.utilities.php';    
require_once 'api_constants.php'; 

//$androidaid_path = '../../androidaid/download/icon/';
$upload_path = API_CONSTANTS::PATH_UPLOAD;

$sha_from = $_POST['sha_from'];

$file_upload_err = $_FILES['uploadedfile']['error'];
$upload_msg = Utilities::checkUploadError($file_upload_err);
$err_msg = $upload_msg;

$originalfile_save = $upload_path
            .Utilities::convertFileName(basename( $_FILES['uploadedfile']['name']));

$target_path  = API_CONSTANTS::PATH_ICON;//接收文件目录  
$icon_save = $target_path.Utilities::convertFileName(basename( $_FILES['uploadedfile']['name']));
//$time = date('YmdHis');
//$icon_save = $target_path.$time.'_'.Utilities::convertFileName(basename( $_FILES['uploadedfile']['name']));

//$androidaid_save = $androidaid_path.Utilities::convertFileName(basename( $_FILES['uploadedfile']['name']));
//$sha = sha1_file($_FILES['uploadedfile']['tmp_name'],false);
//$sha_from = $_POST['sha_from'];

$resp = FALSE;
//if($sha==$sha_from){
        
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $originalfile_save)) { 
//    copy($originalfile_save, $androidaid_save);
	$sha = sha1_file($originalfile_save,false);
	file_put_contents("../json/manager_icon_upload.json",json_encode(Array("file_upload"=>$_FILES['uploadedfile']['name'], "sha"=>$sha, "sha_from"=>$sha_from)));
	if($sha==$sha_from){
		unlink($icon_save);
		if (copy($originalfile_save, $icon_save)){ 
	        $err_msg .= " 图标文件更新成功！";
			$resp =TRUE;
	    }
	    else{
	        $err_msg .= " 图标文件已经存在！";
	    }
	}
    else{
    	$err_msg .= " 文件sha1校验失败！";
    }
    unlink($originalfile_save);
}  
else{  
    $err_msg .= " 图标文件移动失败！";
}
  
//}
//else{
//	$err_msg .= " 文件sha1校验失败！";
//}

$array = Array(API_CONSTANTS::API_RESP => $resp, API_CONSTANTS::API_RESP_MSG => $err_msg);

echo json_encode($array);


    
?>