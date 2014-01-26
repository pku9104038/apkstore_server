<!DOCTYPE html>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<style>
	body 
	{
		background-color:#CCCCCC;
	}
	
	p.introduce
	{
		color:rgb(0,0,0);
		ont-size:28px;
	}
	
</style>

</head>

<body>
	<p class="introduce">
		<?php
			$serial = $_REQUEST['serial'];
			require_once '../utilities/class.applicationmanager.php';
			$mgr = new ApplicationManager();
			
			$introduce = $mgr->getApplicationIntroduce($serial+0);//, $package, $iconfile);
			if ($introduce) {
				echo $introduce;	
			}
			else{
				echo "欢迎下载体验！";
			}
					
			
			
		?>	
	</p>
</body>

</html>

