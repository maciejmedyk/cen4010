<?php
include_once("session.php"); 
if($_POST["action"] == "driverNewpass"){
	$dID = $_POST['dID'];
	$pass = gPassword();
	$query = "UPDATE drivers SET 
				dPassword ='$pass '
				WHERE dID='$dID'";
    $db->query($query);
	echo "New Password ". $pass;
}


if($_POST['action'] == "unlockDriver"){
	unLock($_POST['dID']);
}

if($_POST['action'] == "retireDriver"){
	retireDriver($_POST['dID'], $_POST['step']);
}


if($_POST["action"] == "submitDriverEdit"){
	$dID            = $_POST['dID'];
	$fName          = $_POST['fName'];
	$lName          = $_POST['lName'];
	$email          = $_POST['email'];
	$phone          = $_POST['phone'];
	$license        = $_POST['license'];
	$make           = $_POST['make'];
	$model          = $_POST['model'];
	$year           = $_POST['year'];
	$tag            = $_POST['tag'];
	$insurance      = $_POST['insurance'];
	$policyNumber   = $_POST['policyNumber'];
	$delNotes       = $_POST['delNotes'];
	$schedule       = $_POST['schedule'];
	$schedule = implode(",", $schedule);
	
	$query = "UPDATE drivers SET 
				dFirstName ='$fName ', 
				dLastName ='$lName', 
				dPhoneNumber ='$phone', 
				dEmail ='$email', 
				dLicenseNumber ='$license', 
				dVehicleYear ='$year', 
				dVehicleMake ='$make', 
				dVehicleModel ='$model', 
				dVehicleTag ='$tag', 
				dInsuranceCo ='$insurance', 
				dInsurancePolicy ='$policyNumber',
				dStatusComment = '$delNotes',
				dSchedule = '$schedule'
				WHERE dID='$dID'";
				//echo $query;
    $db->query($query);
}


if($_POST["action"] == "submitNewDriver"){
	
	$fName          = $_POST['fName'];
	$lName          = $_POST['lName'];
	$email          = $_POST['email'];
	$phone          = $_POST['phone'];
	$license        = $_POST['license'];
	$make           = $_POST['make'];
	$model          = $_POST['model'];
	$year           = $_POST['year'];
	$tag            = $_POST['tag'];
	$insurance      = $_POST['insurance'];
	$policyNumber   = $_POST['policyNumber'];
	$delNotes       = $_POST['delNotes'];
	$schedule       = $_POST['schedule'];
	$schedule = implode(",", $schedule);
	$Active         = $_POST['Active'];
	
	//Generate UserName
	$userName = gUsername($fName,$lName);
	//Generate Password
	$pass = gPassword();
	
	/*if($Active == "true"){
		$Active = 1;
	} else {
		$Active = 0;
	}*/
	
	
	$query = "INSERT INTO drivers (dFirstName,dLastName, dPhoneNumber, dEmail, dLicenseNumber, dVehicleYear, dVehicleMake, dVehicleModel, dVehicleTag, dInsuranceCo, dInsurancePolicy, dUsername, dPassword, dActive, dStatusComment, dSchedule) 
			VALUES ('$fName', '$lName', '$phone', '$email', '$license', '$year', '$make', '$model', '$tag', '$insurance', '$policyNumber', '$userName', '$pass', '$Active', '$delNotes', '$schedule')";
	echo $query;
    $db->query($query);
	
	echo "<h2>Driver Added<h2>";
	echo "<h3>".$userName."</h3><h3> ".$pass."</h3>";
}
?>