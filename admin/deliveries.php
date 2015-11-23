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
		
		
		<?php 
			//initRoutes();
		?>
		
		<div class="content">
		
		<ul id="tabs">
		  <li><a href="#clients" class="selected">Deliveries</a></li>
		  <li><a href="#addClientTab">Generate Delivery</a></li>
		</ul>
		<div class="tabContent" id="clients">
			<div class="row" style="padding: 10px 30px;">
				<div id="delYesterday" class="col-md-4 btn btn-success" ><< Yesterday</div> 
				<div id="delToday" class="col-md-4 btn btn-success">Today</div> 
				<div id="delTomorrow" class="col-md-4 btn btn-success">Tomorrow >></div>
			</div>
			<div id="displayData">
				<?php unixTime('11/23/2015');?>
				<?php getDeliverys(date('W'),getTodaysDay(date('w')),0);?>
			</div>
		</div>
<!--

Tab with form to add clients below

-->

		<div class="tabContent hide" id="addClientTab">
			
						
			<div class="row" style="padding: 10px 30px;">
				<div id="genCopy" class="col-md-4 btn btn-success" >Use Last Weeks</br> <?php rangeWeek( date("Y-m-d", time() - 604800) );?></div> 
				<div id="genNew" class="col-md-4 btn btn-success">Create New Schedule For</br> <?php rangeWeek( date("Y-m-d", time()) );?></div> 
				<div id="genToday" class="col-md-4 btn btn-success">Recalucate Today </br> <?php echo date("Y-m-d", time());?></div>
			</div>
		</div>
		
		<!-- CONTENT ENDS HERE-->
	</div>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<?php include("footer.php");?>
	<script langauge="javascript">
		//window.setInterval("deliveryRefresh()", 30000);
	</script>
	
</body>
</html>