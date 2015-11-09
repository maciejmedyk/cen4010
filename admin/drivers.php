<?php
	include_once("session.php"); 
	include_once("header.php");
	$page = "Drivers";
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
						<input id="searchIN" type="hidden" value="driver">
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
		<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
			<div class="page-header pull-left">
				<div class="page-title">
					Drivers
				</div>
			</div>
			<div class="clearfix">
			</div>
		</div>
		<!-- CONTENT STARTS HERE-->
		<div class="content">
            <form>
                <fieldset>
                    <legend>Filters:</legend>
                        <input id="showInactiveDriver" type="checkbox" value="show" name="showInactive"/> Show inactive drivers.
                </fieldset>
            </form> 

		<ul id="tabs">
		  <li><a href="#driverInfoTab" class="selected">Drivers</a></li>
		  <li><a href="#addDriversTab">Add Drivers</a></li>
		</ul>
		<div class="tabContent" id="driverInfoTab">
			<div id="displayData">
				<?php getDrivers(0,"all");?>
			</div>
		</div>
		<div class="tabContent hide" id="addDriversTab">
			<div class="container">
				<form id="editDriverForm" class="form-horizontal" action="#" role="form" method="post">

					<div class="form-group">
						<label class="control-label col-sm-2" for="fName">*First Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="fName" name="fName" placeholder="Enter first name">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="lName">*Last Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="lName" name="lName" placeholder="Enter last name">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">*Email:</label>
						<div class="col-sm-6">
							<input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="phone">*Phone Number:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="dLicense">Drivers License #:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="dLicense" name="dLicense" placeholder="Enter drivers license number">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="vehMake">Vehicle Make:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="vehMake" name="vehMake" placeholder="Enter vehicle's make">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="vehModel">Vehicle Model:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="vehModel" name="vehModel" placeholder="Enter vehicle's model">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="vehYear">Vehicle Year:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="vehYear" name="vehYear" placeholder="Enter vehicle's year">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="vehTag">Vehicle Tag:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="vehTag" name="vehTag" placeholder="Enter vehicle's tag">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="insCo">Insurance Company:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="insCo" name="insCo" placeholder="Enter insurance company">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="insPolicy">Policy Number:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="insPolicy" name="insPolicy" placeholder="Enter insurance policy number">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="notes">Driver Notes:</label>
						<div class="col-sm-6">
							<textarea id="delNotes" name="notes" class="form-control" rows="6" style="min-width: 100%"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div class="checkbox">
								<legend>Delivery Days *</legend>
								<label><input type="checkbox" name="schedule" value="Mo">Monday</label>
								<label><input type="checkbox" name="schedule" value="Tu">Tuesday</label>
								<label><input type="checkbox" name="schedule" value="We">Wednesday</label>
								<label><input type="checkbox" name="schedule" value="Th">Thursday</label>
								<label><input type="checkbox" name="schedule" value="Fr">Friday</label>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div id="addDriver" class="btn btn-success">Add Driver</div>
						</div>
					</div>

				</form>
				<!--<div id="errorMSG"></div>-->
			</div>
		</div>

			<div>
			
				
		</div>	
		<!-- CONTENT ENDS HERE-->
	</div>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<?php include("footer.php");?>
</body>
</html>
