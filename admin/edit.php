<?php
include_once("session.php"); 
if($_POST["action"] == "clientEdit"){
	editClient($_POST['cID']);
}
?>