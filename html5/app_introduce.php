<!DOCTYPE html>
<html>
<head>

<style>
	body 
	{
		background-image:url('../images/bg_normal1.jpg');
	}
	
	p.introduce
	{
		color:rgb(255,255,255);
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

