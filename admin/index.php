<?php
include_once("session.php"); 
include_once("../header.php"); 
include_once("../incnavmenu.php")
?>
<body>
<div>
    <?php include("menu.php"); ?>
    <!--<a href="alogout.php"<button type="link" id="logoutButton" class="btn btn-default">      Log Out       </button></a>-->
</div><br>
<div id="aWireFrame1">

</div>
<div id="aWireFrame2">
    <b align="center" id="welcome">Admin : <?php echo $login_name; ?></b>
</div>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
