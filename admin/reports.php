<?php
	include_once("session.php"); 
	include_once("header.php");
	$page = "Reports";
	?>
<body>
    <!-- Top menu bar -->
	<div id="header-topbar-option-demo" class="page-header-topbar">
		<nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3" class="navbar navbar-default navbar-static-top">
			<?php include("logo.php");?>
			<div class="topbar-main">
				<a id="menu-toggle" href="#" class="hidden-xs"><i class="fa fa-bars"></i></a>
				
				<?php include("info.php");?>
			</div>
		</nav>
	</div>
    
    <!-- Side Menu Bar -->
	<div><?php include("menu.php"); ?></div>
    
	<div id="page-wrapper">
        
    <!-- Page Title -->
		<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
			<div class="page-header pull-left">
				<div class="page-title">
					Reports
				</div>
			</div>
			<div class="clearfix"></div>
            
            <!-- Universal error or success message. Automatically called with errorMSG(message, type) -->
            <div id="">
                <div id="errorWrapper" class="alert alert-dismissable" style="display: none">
                    <button id="closeError" type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <div id="errorMSG"></div>
                </div>
            </div>
		</div>
        
        
        <!-- Main Content goes here -->
        <div class="container-fluid">



            
            
            
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
    
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<?php include("footer.php");?>
</body>
</html>