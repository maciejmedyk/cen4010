<?php
include_once("session.php"); 
include_once("../header.php"); 
?>
<body>
<div>
    <?php include("menu.php"); ?>
    <!--<a href="alogout.php"<button type="link" id="logoutButton" class="btn btn-default">      Log Out       </button></a>-->
</div><br>
<div id="aWireFrame1">
<div>Search: <input id="search" type="text">
<input id="searchIN" type="hidden" value="client"></div>
<div>Add</div>
</div>
<div id="aWireFrame2">
    <b align="center" id="welcome">Admin : <?php echo $login_name; ?></b>
	<div class="row">
		<div class="title">
			<div class="col-md-6">Full Name</div>
			<div class="col-md-2">Phone#</div>
			<div class="col-md-1">Active</div>
			<div class="col-md-2">Action</div>
		</div>
	<div id="displayData">
	<?php getClient(0,"all");?>
	</div>
	</div>
</div>
<?php include_once("footer.php");?>
</body>
</html>
