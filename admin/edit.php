<?php
include_once("session.php"); 
if($_POST["action"] == "clientEdit"){
	editClient($_POST['cID']);
}

if($_POST['action']== "clientDelete"){
	deleteClient($_POST['cID']);
}

if($_POST["action"] == "submitClientEdit"){
	$cID      = $_POST['cID'];
	$fName    = $_POST['fName'];
	$lName    = $_POST['lName'];
	$email    = $_POST['email'];
	$phone    = $_POST['phone'];
	$addr1    = $_POST['addr1'];
	$addr2    = $_POST['addr2'];
	$city     = $_POST['city'];
	$state    = $_POST['state'];
	$zip      = $_POST['zip'];
	$delNotes = $_POST['delNotes'];
	$FA       = $_POST['FA'];
	$FR       = $_POST['FR'];
	$Active   = $_POST['Active'];
	
	if($FA == "true"){
		$FA = 1;
	} else {
		$FA = 0;
	}
	
	if($FR == "true"){
		$FR = 1;
	} else {
		$FR = 0;
	}
	
	if($Active == "true"){
		$Active = 1;
	} else {
		$Active = 0;
	}
	
	echo $FA." ".$FR." ".$Active." </br>";
	
	
	$query = "UPDATE clients SET 
				cFirstName ='$fName ', 
				cLastName ='$lName', 
				cAddress1 ='$addr1', 
				cAddress2 ='$addr2', 
				cCity ='$city', 
				cState ='$state', 
				cZip ='$zip', 
				cPhone ='$phone', 
				cFoodAllergies ='$FA', 
				cFoodRestrictions ='$FR', 
				cDeliveryNotes ='$delNotes',
				cActive = '$Active'
				WHERE cID='$cID'";
    $db->query($query);
	
	echo $query;
}
?>