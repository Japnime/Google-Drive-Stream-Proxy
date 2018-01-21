<?php  
    error_reporting(0);
    require_once(__DIR__ .'/class.DriveProxy.php');
    
    if($_GET['id'] != ""){
        $id = $_GET['id'];
        $drive = new DriveProxy();
        $drive->driveid($id);
    }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Google Drive Proxy Player</title>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	    <style>
            html,body{
            	height: 100%;
            	width: 100%;
            	margin: 0;
            }
	    </style>
	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	<body>
<div id="myElement"></div>
    <script src="https://content.jwplatform.com/libraries/DbXZPMBQ.js"></script>
	<script>
		var playerInstance = jwplayer("myElement");
		playerInstance.setup({
			sources: <?php $drive->proxy_link(); ?>,
			image: "",
			width:"100%",
			height:"100%",
			autostart: "false",
		});
	</script>
    </body>
</html>
