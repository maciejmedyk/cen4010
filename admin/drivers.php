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
					Drivers
				</div>
			</div>
			<div class="clearfix">
			</div>
		</div>
		<!-- CONTENT STARTS HERE-->
		<div class="content">
		
		<ul id="tabs">
		  <li><a href="#about" class="selected">Drivers</a></li>
		  <li><a href="#advantages">Add Drivers</a></li>
		</ul>
		<div class="tabContent" id="about">
		  
			
				<div class="table-row row">
				<div class="title">
					<div class="col-md-6">Full Name</div>
					<div class="col-md-2">Phone#</div>
					<div class="col-md-1">Active</div>
					<div class="col-md-2">Action</div>
				</div>
				<div id="displayData">
				<?php getDrivers(0,"all");?>
				</div>
				</div>
		</div>
		<div class="tabContent hide" id="advantages">
		  <form id="editClientForm" role="form" method="post">

                <div class="form-group input-group row">
				
                    <label class="col-md-3 control-label">First Name</label>
					<div class="col-md-9">
					  <input id="fName" type="text" class="form-control" name="fName" >
					</div>
					
					<label class="col-md-3 control-label">Last Name</label>
					<div class="col-md-9">
					  <input id="lName" type="text" class="form-control" >
					</div>
					
                   
                    
                </div>
                <div class="form-group input-group row">
                    <label class="col-md-3 control-label">Email</label>
					<div class="col-md-9">
					  <input id="email" type="text" class="form-control" >
					</div>
					<label class="col-md-3 control-label">Phone#</label>
					<div class="col-md-9">
					  <input id="phone" type="text" class="form-control" >
					</div>
                    
                </div>
                
                <div class="form-group input-group row">
                    <label class="col-md-3 control-label">Address</label>
					<div class="col-md-9">
					  <input id="addr1" type="text" class="form-control" >
					</div>
					<label class="col-md-3 control-label">Address 2</label>
					<div class="col-md-9">
					  <input id="addr2" type="text" class="form-control" >
					</div>
					<label class="col-md-3 control-label">City</label>
					<div class="col-md-9">
					  <input id="city" type="text" class="form-control" >
					</div>
					<label class="col-md-3 control-label">Zip</label>
					<div class="col-md-9">
					  <input id="zip" type="text" class="form-control" >
					</div>
					<label class="col-md-3 control-label">State</label>
					<div class="col-md-9">
					  <input id="state" type="text" class="form-control">
					</div>
					
                   
                </div>
                <div class="form-group input-group row">
                    <label>Delivery Notes</label>					
					  <textarea id="delNotes" class="form-control" rows="4" style="min-width: 100%">Spice girl fan</textarea>                    
                </div>
                <div class="checkbox row">
					<label><input id="FA" type="checkbox" value="1">Food Allergies</label>
					<label><input id="FR" type="checkbox" value="1">Food Restrictions</label>
                </div>
				<div id="errorMSG"></div>
                <div id="addClient" class="btn btn-success">Add Client</div>
            </form>
		</div>

			<div>
			
				
		</div>	
		<!-- CONTENT ENDS HERE-->
	</div>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<?php include("footer.php");?>
</body>
</html>
