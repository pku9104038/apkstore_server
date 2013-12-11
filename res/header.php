<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php 

    $xml = simplexml_load_file('../conf/app_conf.xml');
    $json = json_encode($xml);
    $obj = json_decode($json);
        
    $app_name = $obj->APP_NAME;  
    $app_org = $obj->APP_ORG;

    if (!isset($page_title)){
        $page_title = $app_org;
    }
    echo "<title>$app_name - $page_title</title>";    
    
//    $_SESSION['back_page_name'] = $page_title;
//    $_SESSION['back_page_link'] = $_SERVER ['PHP_SELF'];
    
?>

  <link rel="stylesheet" type="text/css" href="../styles/style.css" />
  <link rel="shortcut icon" href="../images/favicon.ico" >
</head>
<body>
	<table width=100%">
		<tr>
			<td width="30%">
				<div align="left">
<?php 
    require '../res/logo_left.php';
?>
				</div>
			</td>
			<td width="40%">
		    <div align="center">
			    <h1><?php echo $page_title; ?></h1>
		    </div>
			</td>
			<td width="30%">
		    <div align="right">
			    <h2><?php echo $app_name; ?></h2>
		    </div>
			</td>
		</tr>
	</table>