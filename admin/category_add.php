<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "添加新应用类型！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "category_manager.php";
$link_name = '返回"应用类型基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php
    $category = "";
    $notes = "";
    $captcha = "";

    $err_captcha = "";
    $err_category = "";
    $err_msg = "";


if (isset ( $_POST ['submit'] )) 
{
    $serial = $_POST ['serial'];
    $category = $_POST ['category'];
    $group_serial = $_POST ['group_serial'];
    $notes = $_POST['notes'];
    $captcha = sha1 ( $_POST ['captcha'] );

    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    require_once '../utilities/class.categorymanager.php';
    $mgr = new CategoryManager();
    if( $mgr->checkCategory($category)){
        $err_group = "该应用类型已经存在！";
        $output_form = TRUE;
    }
    
    
    if (!$output_form){
        $err_msg = '新应用类型"'.$category;
        if($mgr->addCategory($category, $group_serial, $notes)){
            $err_msg .= '"添加成功！';
        }
        else{
            $err_msg .= '"添加失败！';
            $output_form = TRUE;
        }
    }
    
}
else {
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
									<div align="right"><label for="category">类型名称：</label></div>
								</td>
								<td >
									<input id="category" name="category" type="text" value="<?php echo $category; ?>" />
									<label class="error"><?php echo $err_category; ?></label>
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

    require_once '../utilities/class.groupmanager.php';
    $groupMgr = new GroupManager();
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
					<p><a href="category_add.php">添加下一个？</a></p>
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
