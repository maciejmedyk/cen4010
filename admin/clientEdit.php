<?php
	include_once("session.php"); 
	include_once("header.php"); 
    $page = "Clients";
	?>
<body>
	<div id="header-topbar-option-demo" class="page-header-topbar">
		<nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3" class="navbar navbar-default navbar-static-top">
			<?php include("logo.php");?>
			<div class="topbar-main">
				<a id="menu-toggle" href="#" class="hidden-xs"><i class="fa fa-bars"></i></a>
				<form id="topbar-search" action="" method="" class="hidden-sm hidden-xs" _lpchecked="1">
					<div class="input-icon right text-white">
						<a href="#"><i class="fa fa-search"></i></a>
						<input id="search" type="text" placeholder="Search here..." class="form-control text-white">
						<input id="searchIN" type="hidden" value="client">
					</div>
			
				</form>
				<?php include("info.php");?>
			</div>
		</nav>
	</div>
	<div>
		<?php include("menu.php"); ?>
	</div>
	<div id="page-wrapper">
    <!-- Page Title -->
		<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
			<div class="page-header pull-left">
				<div class="page-title">
					Edit Client
				</div>
			</div>
			<div class="clearfix"></div>
            
            <!-- Universal error or success message. Called with errorMSG(message, type) -->
            <div id="">
                <div id="errorWrapper" class="alert alert-dismissable" style="display: none">
                    <button id="closeError" type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <div id="errorMSG"></div>
                </div>
            </div>
		</div>
		<!-- CONTENT STARTS HERE-->
		<div class="content">
		
		
		<div class="tabContent" id="clients">
			
            <div class="container">
                <div id="displayData">
                    <?php editClient($_GET['cID']);?>
                </div>
			</div>
		</div>
<!--

Tab with form to add clients below

-->


		<!-- CONTENT ENDS HERE-->
	</div>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<?php include("footer.php");?>
</body>
</html>
