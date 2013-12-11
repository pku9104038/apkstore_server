<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "添加新分组！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "group_manager.php";
$link_name = '返回"应用分组基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php
    $group = "";
    $icon = "";
    $priority = "";
    $notes = "";
    $captcha = "";

    $err_captcha = "";
    $err_group = "";
    $err_priority = "";
    $err_msg = "";
    $upload_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $group_serial = $_POST ['group_serial'];
    $group = $_POST ['group'];
    $icon = $_POST ['icon'];
    $priority = $_POST ['priority'] + 0;
    $notes = $_POST['notes'];
    $captcha = sha1 ( $_POST ['captcha'] );

    $output_form = FALSE;
    
    $iconfile_upload = $_FILES['iconfile']['error'];
    $iconfile_path = $_FILES['iconfile']['tmp_name'];
    $iconfile_original = $_FILES['iconfile']['name'];
    $iconfile_type = $_FILES['iconfile']['type'];
    $iconfile_size = $_FILES['iconfile']['size'];
    
    require_once '../utilities/class.groupmanager.php';
    require_once '../api/api_constants.php';
                
          
    $mgr = new GroupManager();
    $save = API_CONSTANTS::PATH_GUI.$_FILES['iconfile']['name'];
    switch($iconfile_upload){
        case 0:
            $upload_msg = "文件上传成功!";
            break;
        case 1:
            $upload_msg = "文件大小超出服务器空间限制！";
        case 2:
            $upload_msg = "文件大小超出浏览器限制！";
        case 3:
            $upload_msg = "文件仅部分被上传！";
        case 4:
            $upload_msg = "没有找到要上传的文件！";
        case 5:
            $upload_msg = "服务器临时文件丢失！";
        case 6:
            $upload_msg = "写入临时文件夹出错！";
            
    }
    if ($iconfile_upload>0){
//        $output_form = TRUE;
    }
    else{
        if(copy($_FILES['iconfile']['tmp_name'],$save)){
            $icon = $_FILES['iconfile']['name'];
            $upload_msg .= " ".$iconfile_type." 文件:".$iconfile_original." 大小:".$iconfile_size;
        }
        else{
            $upload_msg .= "文件拷贝出错！";
//            $output_form = TRUE;
        }
    }
    

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    if( $mgr->checkGroup($group)){
        $err_group = "该分组已经存在！";
        $output_form = TRUE;
    }
    
    if($priority<2 && $group_serial != 16){
    	$err_msg .= '优先级1为系统保留功能，请使用优先级2以上！';
    	$output_form = TRUE;
    }
    
    if (!$output_form){
        if($mgr->addGroup($group, $icon, $priority,$notes)){
            $err_msg = '新分组"'.$group.'"添加成功！';
        }
        else{
            $err_msg = '新分组"'.$group.'"添加失败！';
            $output_form = TRUE;
        }
    }
    
}
else {
    $output_form = TRUE;
}
if ($output_form) {
?>

				<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
					<div align="center">
						<p class="error"><?php echo $err_msg?></p>
						<p class="error"><?php echo $upload_msg?></p>
						<table>
							<tr>
								<td>
									<div align="right"><label for="group">分组名称：</label></div>
								</td>
								<td >
									<input id="group" name="group" type="text" value="<?php echo $group; ?>" />
									<label class="error"><?php echo $err_group; ?></label>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="iconfile">图标：</label></div>
								</td>
								<td >
									<img src="<?php echo $icon; ?>" alt="图标">
									<input type="file" id="iconfile" name="iconfile" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="priority">优先级：</label></div>
								</td>
								<td >
									<input id="priority" name="priority" type="text" value="<?php echo $priority; ?>" />
									<label class="error"><?php echo $err_priority; ?></label>
								</td>
							</tr>
							<tr>
								<td><div align="right"><label for="notes">备注：</label></div></td>
								<td>
									<textarea id="notes" name="notes" rows="4" cols="40" ><?php echo $notes; ?></textarea>
								</td>
							</tr>
							<tr>
								<td><div align="right"><label for="captcha">验证码：</label></div></td>
								<td>
									<input id="captcha" name="captcha" type="text" value="" />
									<img align="top" src="../res/captcha.php" alt="验证码" />
									<label class="error"><?php echo $err_captcha; ?></label>
								</td>
							</tr>
							<tr>
								<td><br /></td>
							</tr>
							<tr>
								<td></td>
								<td><div align="left"><input type="submit" name="submit" value="确认提交" /></div></td>
							</tr>
						</table>
					</div>
				</form>

<?php
} 
else {
?>
				<div align="center">
						<p class="error"><?php echo $err_msg;?></p>
						<p class="error"><?php echo $upload_msg;?></p>
						<p><a href="group_add.php">添加下一个？</a></p>
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
