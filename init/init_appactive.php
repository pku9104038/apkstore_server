<?php

require_once '../utilities/class.appactivemanager.php';
require_once '../utilities/class.appgroupmanager.php';
require_once '../utilities/class.widgetgroupmanager.php';

$activeMgr = new AppActiveManager();
$appMgr = new AppGroupManager();
$widgerMgr = new WidgetGroupManager();

$array = $appMgr->getRecords("2013-01-01","2013-07-10");
$activeMgr->addRecords($array);
$array =$widgerMgr->getRecords("2013-01-01","2013-07-10");
$activeMgr->addRecords($array);


?>