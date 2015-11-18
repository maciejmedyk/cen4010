<?php
	include_once("session.php"); 
	include_once("header.php");
	$page = "Deliveries";
	?>
<body>
	<div id="header-topbar-option-demo" class="page-header-topbar">
		<nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3" class="navbar navbar-default navbar-static-top">
			<?php include("logo.php");?>
			<div class="topbar-main">
				<a id="menu-toggle" href="#" class="hidden-xs"><i class="fa fa-bars"></i></a>
				
				<?php include("info.php");?>
			</div>
		</nav>
	</div>
	<div>
		<?php include("menu.php"); ?>
	</div>
	<div id="page-wrapper">
		<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
			<div class="page-header pull-left">
				<div class="page-title">
					Deliveries
				</div>
			</div>
			<div class="clearfix">
			</div>
		</div>
		<!-- CONTENT STARTS HERE-->
		<div  class="alert  alert-danger">   
                <h4 id="errorMSG" class="alert-heading"></h4>  
            </div>
		
		
		
		
		<!-- CONTENT ENDS HERE-->
	</div>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<?php include("footer.php");?>
</body>
</html>