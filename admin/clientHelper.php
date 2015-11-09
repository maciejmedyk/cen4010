<?php
include_once("session.php"); 
if($_POST["action"] == "clientEdit"){
	editClient($_POST['cID']);
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
	
	$address = "$addr1 $addr2 $city $state $zip";
	
	$address = str_replace("#", "", $address);
	$address = str_replace(" ", "-", $address);
	$address = preg_replace("/--+/", "+", $address);
	$address = str_replace("-", "+", $address);
	if(substr($address, -1) == "+"){
		$address = substr($address, 0, -1);
	}
	$json = "";
	$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=USA");
	//echo $json;
	if($json != ""){
		$json = json_decode($json);

		$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		$lng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
		
		if($lat == "" && $lng == ""){
			$lat = $json->{'results'}[0]->{'location'}->{'lat'};
			$lng = $json->{'results'}[0]->{'location'}->{'lng'};
		}else if($lat == ""){
			$lat = 'empty';
			$lng = 'empty';
		}
	}
	
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
				cFirstName ='$fName ', 
				cLastName ='$lName', 
				cAddress1 ='$addr1', 
				cAddress2 ='$addr2', 
				cCity ='$city', 
				cState ='$state', 
				cZip ='$zip',
				cLat ='$lat',
				cLng ='$lng',
				cPhone ='$phone', 
				cFoodAllergies ='$FA', 
				cFoodRestrictions ='$FR', 
				cDeliveryNotes ='$delNotes',
				cActive = '$Active'
				WHERE cID='$cID'";
    $db->query($query);
	
	echo "Client info has been updated ".$fName;
	
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
	
	$address = "$addr1 $addr2 $city $state $zip";
	
	$address = str_replace("#", "", $address);
	$address = str_replace(" ", "-", $address);
	$address = preg_replace("/--+/", "+", $address);
	$address = str_replace("-", "+", $address);
	if(substr($address, -1) == "+"){
		$address = substr($address, 0, -1);
	}
	
	$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=USA");
	$json = json_decode($json);

	$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
	$lng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
	
	if($lat == "" && $lng == ""){
		$lat = $json->{'results'}[0]->{'location'}->{'lat'};
		$lng = $json->{'results'}[0]->{'location'}->{'lng'};
	}else if($lat == ""){
		$lat = 'empty';
		$lng = 'empty';
	}
	
	echo $lat." ".$lng;
	
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
	
	
	$query = "INSERT INTO clients (cFirstName,cLastName,cAddress1,cAddress2,cCity,cState,cZip,cLat,cLng,cPhone,cFoodAllergies,cFoodRestrictions,cDeliveryNotes,cActive) 
							VALUES ('$fName', '$lName', '$addr1', '$addr2', '$city', '$state', '$zip','$lat','$lng', '$phone', '$FA', '$FR', '$delNotes', '1')";
    $db->query($query);
	
	echo "Client Added";
}
?>