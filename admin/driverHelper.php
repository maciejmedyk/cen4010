<?php
include_once("session.php"); 
if($_POST["action"] == "driverEdit"){
	editDriver($_POST['cID']);
}

if($_POST['action']== "clientDelete"){
	actionClient($_POST['cID'],0);
}

if($_POST['action'] == "clientDeleteConfirm"){
	actionClient($_POST['cID'],1);
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
	
	
	$query = "UPDATE clients SET 
				dFirstName ='$fName ', 
				dLastName ='$lName', 
				dAddress1 ='$addr1', 
				dAddress2 ='$addr2', 
				dCity ='$city', 
				dState ='$state', 
				dZip ='$zip', 
				dPhone ='$phone', 
				dFoodAllergies ='$FA', 
				dFoodRestrictions ='$FR', 
				dDeliveryNotes ='$delNotes',
				dActive = '$Active'
				WHERE cID='$cID'";
    $db->query($query);
	
}


if($_POST["action"] == "submitNewClient"){
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
	
	
	$query = "INSERT INTO clients (cFirstName,cLastName,cAddress1,cAddress2,cCity,cState,cZip,cPhone,cFoodAllergies,cFoodRestrictions,cDeliveryNotes,cActive) 
							VALUES ('$fName', '$lName', '$addr1', '$addr2', '$city', '$state', '$zip', '$phone', '$FA', '$FR', '$delNotes', '1')";
    $db->query($query);
	
	echo "Client Added";
}
?>