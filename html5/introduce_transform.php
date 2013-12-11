<?php
require_once '../utilities/class.applicationmanager.php';
$mgr = new ApplicationManager();
	
echo  $mgr->IntroduceTransform()." records transformed!";

?>