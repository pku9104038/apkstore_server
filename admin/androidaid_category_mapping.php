<?php 
require_once 'check_login.php';
$roles_required = Array(
    1     //root
);
require_once 'role_check.php';

$page_title = "应用类型信息修改！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "androidaid_category_manager.php";
$link_name = '返回"应用类型基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

    $err_captcha = "";
    $err_category = "";
    $err_msg = "";

if (isset ( $_POST ['submit'] )) 
{
    $serial = $_POST ['serial'];
    $category_old = $_POST ['category_old'];
    $category = $_POST ['category'];
    $group_serial = $_POST ['group_serial'];
    $captcha = sha1 ( $_POST ['captcha'] );

    $output_form = FALSE;

    //check capthca
    //session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    require_once '../utilities/class.categorymanager.php';
    $mgr = new CategoryManager();
    
    if (!$output_form){
        $err_msg = '应用类型"'.$category;
        if($mgr->updateAndroidAidCategory($serial,$group_serial)){
            $err_msg .= '"信息修改成功！';
        }
        else{
            $err_msg .= '"信息修改失败！';
            $output_form = TRUE;
        }
    }
    
}
else {
    $serial = $_GET ['serial'];
    $category = $_GET ['category'];
    $category_old = $category;
    $group_serial = $_GET ['group_serial'];
    $notes = $_GET['notes'];
    
    $output_form = TRUE;
}
if ($output_form) {
?>

				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<div align="center">
						<p class="error"><?php echo $err_msg?></p>
						<table>
							<tr>
								<td>
									<div align="right"><label for="category_old">类型名称：</label></div>
								</td>
								<td >
									<label class="error"><?php echo $category_old; ?></label>
									<input id="category_old" name="category_old" type="hidden" value="<?php echo $category_old; ?>" />
									<input id="serial" name="serial" type="hidden" value="<?php echo $serial; ?>" />
									<input id="category" name="category" type="hidden" value="<?php echo $category; ?>" />
									</td>
							</tr>
						<tr>
								<td>
									<div align="right"><label for="group_serial">应用分组：</label></div>
								</td>
								<td >
									<select id="group_serial" name="group_serial">
									
<?php 
    require_once '../proxy/class.databaseproxy.php';
    $column_group_serial = DatabaseProxy::DB_COLUMN_GROUP_SERIAL;
    $column_group = DatabaseProxy::DB_COLUMN_GROUP;

    require_once '../utilities/class.androidaidgroupmanager.php';
    $groupMgr = new AndroidaidGroupManager();
    $array_groups = $groupMgr->getGroups();
    for($i=0; $i<count($array_groups); $i++){
        $echostring = '<option value="'.$array_groups[$i]["$column_group_serial"].'"';
        if(!empty($group_serial) && $group_serial==$array_groups[$i]["$column_group_serial"]){
            $echostring .= 'selected = "selected"';
        }
        $echostring .= '>'.$array_groups[$i]["$column_group"].'</option>';
        echo $echostring;
    }
    
?>									

									</select>
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
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
