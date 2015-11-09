<?php
	include_once("session.php"); 
	include_once("header.php"); 
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
		<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
			<div class="page-header pull-left">
				<div class="page-title">
					Edit Client Information <a href="clients.php" class="dTableButton btn btn-xs btn-success float_r">Back To Client List</a>
				</div>
			</div>
			<div class="clearfix">
			</div>
		</div>
		<!-- CONTENT STARTS HERE-->
		<div class="content">
		
		
		<div class="tabContent" id="clients">
			<div id="displayData">
				<?php editClient($_GET['cID']);?>
			</div>
		</div>
<!--

Tab with form to add clients below

-->

		<div class="tabContent hide" id="addClientTab">
			<div class="container">
				<form id="editClientForm" class="form-horizontal" action="#" role="form" method="post">

					<div class="form-group">
						<label class="control-label col-sm-2" for="fName">First Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="fName" name="fName" placeholder="Enter first name">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="lName">Last Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="lName" name="lName" placeholder="Enter last name">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Email:</label>
						<div class="col-sm-6">
							<input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">Password:</label>
						<div class="col-sm-6">
							<input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter password">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="phone">Phone Number:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="address">Address:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="address" name="address" placeholder="Enter street address">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="city">City:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="city" name="city" placeholder="Enter city">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="state">State:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="state" name="state" placeholder="Enter state">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="zip">ZIP Code:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="zip" name="zip" placeholder="Enter zip code">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="delNotes">Delivery Notes:</label>
						<div class="col-sm-6">
							<textarea id="delNotes" name="delNotes" class="form-control" rows="6" style="min-width: 100%"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div class="checkbox">
								<label><input id="FA" type="checkbox" value="1">Food Allergies: </label>
								<label><input id="FR" type="checkbox" value="1">Food Restrictions: </label>
							</div>
						</div>
					</div>
					<div id="errorMSG"></div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div id="addClient" class="btn btn-success">Add Client</div>
						</div>
					</div>

				</form>
			</div>
		</div>

		<!-- CONTENT ENDS HERE-->
	</div>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<?php include("footer.php");?>
</body>
</html>
