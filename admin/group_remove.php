<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "删除应用分组信息！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "group_manager.php";
$link_name = '返回"应用分组基本信息管理"';
require_once '../res/righttop_link.php';
?>

<?php

    $err_captcha = "";
    $err_msg = "";
    

if (isset ( $_POST ['submit'] )) 
{
    $group_serial = $_POST ['group_serial'];
    $group = $_POST ['group'];
    $icon = $_POST ['icon'];
    $priority = $_POST ['priority'] + 0;

    $captcha = sha1 ( $_POST ['captcha'] );
    
    $output_form = FALSE;

    //check capthca
    session_start ();
    if ($captcha != $_SESSION ['captcha']) {
        $err_captcha = "重新输入验证码！";
        $output_form = TRUE;
    }

    
    require_once '../utilities/class.groupmanager.php';
    $mgr = new GroupManager();
   
    if (!$output_form){
        $err_msg = '应用分组"'.$group;
        if($mgr->removeGroup($group)){
            $err_msg .= '"删除成功！';
        }
        else{
            $err_msg .= '"删除失败！';
            $output_form = TRUE;
        }
    }
    
}
else if(isset($_POST['cancel'])){
    header("Location: group_manager.php");
}
else {
    $group_serial = $_GET ['group_serial'];
    $group = $_GET ['group'];
    $icon = $_GET ['icon'];
    $priority = $_GET ['priority'] + 0;

    $captcha = "";
    
    $output_form = TRUE;
}
if ($output_form) {
require_once '../res/url_conf.php';
?>

				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<div align="center">
						<p class="error"><?php echo $err_msg?></p>
						<table>
							<tr>
								<td>
									<div align="right"><label for="group">应用分组：</label></div>
								</td>
								<td >
									<label ><?php echo $group; ?></label>
									<input id="group" name="group" type="hidden" value="<?php echo $group; ?>" />
									<input id="group_serial" name="group_serial" type="hidden" value="<?php echo $group_serial; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="icon">图标：</label></div>
								</td>
								<td >
									<img src="<?php echo $path_groupicons.$icon;?>" alt="图标">
									<input id="icon" name="icon" type="hidden" value="<?php echo $icon; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right"><label for="priority">优先级：</label></div>
								</td>
								<td >
									<label ><?php echo $priority; ?></label>
									<input id="priority" name="priority" type="hidden" value="<?php echo $priority; ?>" />
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
								<td><div align="right"><input type="submit" name="submit" value="确认" /></div></td>
								<td><div align="center"><input type="submit" name="cancel" value="取消" /></div></td>
							</tr>
						</table>
					</div>
				</form>

<?php
} 
else {
?>
				<div align="center">
				<table>
					<tr>
						<td><label class="error"><?php echo $err_msg;?></label></td>
					</tr>
				</table>
				</div>
<?php 
}
?>


<?php 
require_once '../res/footer.php';
?>
