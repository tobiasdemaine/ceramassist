<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Ceramassist</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="demaine ceramics">
		<meta name="author" content="Tobias De Maine > tobias@demaine.co">
		<script src="assets/js/jquery-1.9.0.min.js" type="text/javascript"></script>
		<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="assets/js/jquery-ui-1.10.0.custom.min.js" type="text/javascript"></script>
		<script src="assets/js/google-code-prettify/prettify.js" type="text/javascript"></script>
		<script language="javascript" type="text/javascript" src="assets/js/flot/jquery.flot.js"></script>
		<script language="javascript" type="text/javascript" src="assets/js/json2.min.js"></script>
		<script language="javascript" type="text/javascript" src="assets/js/flot/jquery.flot.time.js"></script>
		<script language="javascript" type="text/javascript" src="assets/js/jquery-ui-timepicker-addon.js"></script>
		
		
		
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="assets/css/custom-theme/jquery-ui-1.10.0.custom.css" rel="stylesheet" />
          <link type="text/css" href="assets/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
        <link type="text/css" href="assets/css/font-awesome.min.css" rel="stylesheet" />
            <!--[if IE 7]>
            <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css">
            <![endif]-->
            <!--[if lt IE 9]>
            <link rel="stylesheet" type="text/css" href="css/custom-theme/jquery.ui.1.10.0.ie.css"/>
            <![endif]-->
        <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">

            <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
            <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <![endif]-->

            <!-- Le fav and touch icons -->
            <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
            <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
            <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
            <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
            <link rel="shortcut icon" href="assets/ico/favicon.png">
            <style>
            	.btn-info{
            		color:white!important;
            	}
            	.ui-tabs .ui-tabs-panel {
  					display: block;
    				border-width: 0;
    				padding: 1em 0;
    				background: none;
				}
				#loader{
					display:none;
					z-index: 100000;
					background: rgba(255, 255, 255, 0.58);
					width:100%;
					height:100%;
					position: absolute;
					top:0px;
					left:0px;
				}
				#loader div{
					text-align: center;
					font-size: 30px;
					margin-top: 25%;
				}
            </style>
            <script>
            	function show_loader(){
            		$("#loader").show();
            	}
            	
            	function hide_loader(){
            		$("#loader").hide();
            	}
            </script>
		</head>
	<body>
		<div style="    background-image: linear-gradient(to bottom, #EAE9E9, #F3F3F3); margin-bottom:10px; position:fixed; width:100%; z-index:1000; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.6)" >
			<div class="container-fluid" >
			<div class="pull-right btn-group" style="padding-top:5px; ">
				<?php if($view->get_section() == "documents"){ $btn_info="btn-success";}else{$btn_info="btw-info";} ?>
				<a href="?documents=true"  class='btn <?=$btn_info;?> btn-sm'>Documents</a>
				<?php if($view->get_section() == "material"){ $btn_info="btn-success";}else{$btn_info="btw-info";} ?>
				<a href="?samples=true"  class='btn <?=$btn_info;?> btn-sm'>Materials</a>
				<?php if($view->get_section() == "formula"){ $btn_info="btn-success";}else{$btn_info="btw-info";} ?>
				<a href="?formula=true"  class='btn <?=$btn_info;?> btn-sm'>Formulae</a>
				<?php if($view->get_section() == "recipes"){ $btn_info="btn-success";}else{$btn_info="btw-info";} ?>
				<a href="?recipes=true"  class='btn <?=$btn_info;?> btn-sm'>Recipes</a>
				<?php if($view->get_section() == "tests"){ $btn_info="btn-success";}else{$btn_info="btw-info";} ?>
				<a href="?tests=true"  class='btn <?=$btn_info;?> btn-sm'>Tests</a>
				<?php if($view->get_section() == "firings"){ $btn_info="btn-success";}else{$btn_info="btw-info";} ?>
        		<a href="?firings=true" class='btn <?=$btn_info;?> btn-sm'>Firings</a>
        		<?php if($view->get_section() == "ware"){ $btn_info="btn-success";}else{$btn_info="btw-info";} ?>
        		<a href="?ware=true" class='btn <?=$btn_info;?> btn-sm'>Ware</a>
				<?php if($view->get_section() == "distribution"){ $btn_info="btn-success";}else{$btn_info="btw-info";} ?>
        		<a href="?distribution=true" class='btn <?=$btn_info;?> btn-sm'>Distribution</a>
        	</div>
			<a href="?">
        		<img src="assets/img/logo.png" style="height:24px; margin-bottom:8px; margin-top:8px;">
        	</a>
        	</div>
        </div>
        <div class="container-fluid" style="padding-top:50px;">
        	<!--content-->
        </div>
        <div id="loader">
        	<div>
        		Please Wait. Internet activity in progress.
        	</div>
        </div>
    </body>
</html>
