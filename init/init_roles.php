<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">

<?php

require_once '../utilities/class.accountmanager.php';


$mgr = new AccountManager();
	
if (!$mgr->isRootAvailable()){
	if($mgr->initRoot()){
			
		echo "root inited";
	}
	else{
	
		echo "root init failed";
		
	}
}
else{
	echo "root available!";
}



?>


</html>
