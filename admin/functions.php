<?php

//Used to log php info to the javascript console.
function consoleLog($msg){
    echo "<script>console.log('$msg');</script>";
}

//Redirect to different page.
function Redirect($url, $permanent = false)
{
    if (headers_sent() === false) {
    	header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }
    exit();
}

function getClient($id, $count){
    include('../connection.php');
	if($count == "all"){
        $query = "SELECT cID, cFirstName, cLastName, cPhone, cActive, cDeliveryNotes
				FROM clients
				ORDER BY cLastName ASC";
    }elseif($count == "search"){
        $query = "SELECT cID, cFirstName, cLastName, cPhone, cActive, cDeliveryNotes 
            FROM clients
            WHERE cFirstName LIKE '%$id%' OR cLastName LIKE '%$id%' OR cPhone LIKE '%$id%'
            ORDER BY cLastName ASC";
    }
    
	$sql = $db->query($query);
	$row_cnt = $sql->num_rows;
    if ($row_cnt == 0){
        if ($count == "all") echo "<div class='alert alert-warning fade in msg'>There are currently no clients in the database.</div>";
        if ($count == "search")echo "<div class='alert alert-warning fade in msg'>There are currently no clients that match that query.</div>";
    } else {
        echo "<table class='alignleft table table-hover'>
            <thead class='tableHead'>
            <tr>
            <th><i class='fa fa-check-square'></iclass></th>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Delivery Notes</th>
            </tr>
            </thead>
            <tbody>";


        while ($info = $sql->fetch_array()) {
            if($info['cActive'] == 1){
                $active ="Active";
                #$action = "Deactivate";
            } else {
                $active ="Inactive";
                #$action = "Activate";
            }
            echo "<tr>
                <td><a href='clientEdit.php?cID=".$info['cID']."' class='dTableButton btn btn-xs btn-success' data-driverID='" . $info['cID'] . "'>Edit</a></td>
                <td>" . $info['cID'] . "</td>
                <td>" . $info['cLastName'] . " " . $info['cFirstName'] . "</td>
                <td>" . $info['cPhone'] . "</td>
                <td>" . $active . "</td>
                <td>" . $info['cDeliveryNotes'] . "</td>
            </tr>";
        }
        echo "</tbody></table>";
    }
}

function getDrivers($id, $count){
    include('../connection.php');
	if($count == "all"){
        $query = "SELECT *
                FROM drivers
                ORDER BY dLastName ASC";
    }elseif($count == "search"){
        $query = "SELECT *
            FROM drivers
            WHERE dFirstName LIKE '%$id%' OR dLastName LIKE '%$id%' OR dUsername LIKE '%$id%'
            ORDER BY dLastName ASC";
    }

    $sql = $db->query($query);
    $row_cnt = $sql->num_rows;
    if ($row_cnt == 0){
        if ($count == "all") echo "<div class='alert alert-warning fade in msg'>There are currently no drivers in the database.</div>";
        if ($count == "search") echo "<div class='alert alert-warning fade in msg'>There are currently no drivers that match your query.</div>";
    } else {
        echo "<table id='driverTable' class='alignleft table table-hover'>
        <thead class='tableHead'>
        <tr>
        <th width='25%'><i class='fa fa-check-square'></iclass></th>
        <th width='5%'>ID</th>
        <th width='10%'>Name</th>
        <th width='10%'>Username</th>
        <th width='15%'>Vehicle</th>
        <th width='10%'>Vehicle Tag</th>
        <th width='10%'>Insurance</th>
        <th width='10%'>Insurance Policy</th>
        <th width='5%'>Status</th>
        </tr>
        </thead>
        <tbody>";

        while ($info = $sql->fetch_array()) {
            if($info['dActive'] == 1){
                $active ="Yes";
                $action = "Deactivate";
            } else {
                $active ="No";
                $action = "Activate";
            }

            if(isRetired($info['dID']) ){
                $dlabel = "Retire";
                $status = "Active";
            } else {
                $dlabel = "Re-enable";
                $status = "Retired";
            }

            if(isLocked($info['dID']) ){
                $locked = "<a href='driverEdit.php?dID=" . $info['dID'] . "' class='dTableButton btn btn-xs btn-danger' data-driverID='" . $info['dID'] . "'>Unlock Driver</a>";
            } else {
                $locked = "";
            }

            echo "<tr data-status='" . $status . "'>
                <td>
                    <a href='driverEdit.php?dID=" . $info['dID'] . "' class='dTableButton btn btn-xs btn-success' data-driverID='" . $info['dID'] . "'>Edit</a>						
                    <a href='driverEdit.php?dID=" . $info['dID'] . "' class='dTableButton btn btn-xs btn-success' data-driverID='" . $info['dID'] . "'>".$dlabel." Driver</a>
                    ".$locked."
                </td>
                <td>" . $info['dID'] . "</td>
                <td>" . $info['dLastName'] . " " . $info['dFirstName'] . "</td>
                <td>" . $info['dUsername'] . "</td>
                <td>" . $info['dVehicleYear'] . " " . $info['dVehicleMake'] . " " . $info['dVehicleModel'] . "</td>
                <td>" . $info['dVehicleTag'] . "</td>
                <td>" . $info['dInsuranceCo'] . "</td>
                <td>" . $info['dInsurancePolicy'] . "</td>
                <td>" . $status. "</td>
            </tr>";
        }
        echo "</tbody></table>";
    }
}

function isRetired($dID){
	include('../connection.php');
	$query = "SELECT *
				FROM drivers
				WHERE dID = $dID AND dActive = 0";

	$sql = $db->query($query);
	$row_cnt = $sql->num_rows;
	if ($row_cnt == 0){
		return true;
	} else {
		return false;
	}
}

function isLocked($dID){
	include('../connection.php');
	$query = "SELECT *
				FROM trap
				WHERE dID = $dID AND lockCount > 9";

	$sql = $db->query($query);
	$row_cnt = $sql->num_rows;
	if ($row_cnt > 0){
		return true;
	} else {
		return false;
	}
}

function searchClient($name){
    getClient($name, "search");
}

function searchDriver($name){
    getDrivers($name, "search");
}

function actionClient($clientID, $step){
	include('../connection.php');
	if($step == 1){
		
		$query = "SELECT *
					FROM clients
					WHERE cID = $clientID";
		$sql = $db->query($query);
		$info = $sql->fetch_array();
		$active = $info['cActive'];
		echo $active;
		
		if($active == 1){
			$active = 0;
		} else{
			$active = 1;
		}
		
		
		$query = "UPDATE clients SET 
				cActive = '$active'
				WHERE cID='$clientID'";
		
					
		$db->query($query);
		
		echo '<div class="formTitle">Client Deactivated</div>';
	} else {
		$query = "SELECT *
					FROM clients
					WHERE cID = $clientID";
		$sql = $db->query($query);
		$info = $sql->fetch_array();
		echo '<div class="formTitle">Delete Client Information</div>';
		echo '<div>Do you realy want to delete '. $info['cFirstName'] .' '. $info['cLastName'] .' .</div>';
		echo '<div onclick="actionClient('.$clientID.',1)" >Yes</div> <div onclick="actionClient('.$clientID.',0)">No</div>';
	}
}

function editClient($clientID){
	include('../connection.php');
	$query = "SELECT *
				FROM clients
				WHERE cID = $clientID
				ORDER BY cLastName ASC";
	$sql = $db->query($query);
	$info = $sql->fetch_array();
    
    $activeChecked = "";
    if ($info['cActive'] == 1){
        $activeChecked = "checked";
    }
    
	//echo '<div class="formTitle">Edit Client Information</div>';
    echo ' 
    
            <br/>
            <form id="editClientForm" class="form-horizontal" action="#" role="form" method="post">
                    <input id="cID" type="hidden" value="'.$clientID.'">
					<div class="form-group">
						<label class="control-label col-sm-2" for="fName">First Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="fName" name="fName" value="'.$info['cFirstName'].'" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="lName">Last Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="lName" name="lName" value="'.$info['cLastName'].'" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Email:</label>
						<div class="col-sm-6">
							<input type="email" class="form-control" id="email" name="email" value="'.$info['cEmail'].'">
						</div>
					</div>
					<!--<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">Password:</label>
						<div class="col-sm-6">
							<input type="password" class="form-control" id="pwd" name="pwd" value="">
						</div>
					</div>-->
					<div class="form-group">
						<label class="control-label col-sm-2" for="phone">Phone Number:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="phone" name="phone" value="'.$info['cPhone'].'" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="address">Address:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="addr1" name="address" value="'.$info['cAddress1'].'" required>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="address">Address:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="addr2" name="address" value="'.$info['cAddress2'].'">
						</div>
					</div>
					
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="city">City:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="city" name="city" value="'.$info['cCity'].'" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="state">State:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="state" name="state" value="'.$info['cState'].'" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="zip">ZIP Code:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="zip" name="zip" value="'.$info['cZip'].'" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="delNotes">Delivery Notes:</label>
						<div class="col-sm-6">
							<textarea id="delNotes" name="delNotes" class="form-control" rows="6" value="'.$info['cDeliveryNotes'].'" style="min-width: 100%"></textarea>
						</div>
					</div>
                    
                    
                    
                    <div class="form-group">
						<label class="control-label col-sm-2" for="FAList">Food Alergies:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="FAList" name="FAList" value="'.$info['FAList'].'" placeholder="Example: nuts,shellfish,wheat">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="zip">Food Restrictions:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="FRList" name="FRList" value="'.$info['FRList'].'" placeholder="Example: milk,bacon">
						</div>
					</div>
                    
                    
                    
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div class="checkbox">
								<!--label><input id="FA" type="checkbox" value="1">Food Allergies </label>
								<label><input id="FR" type="checkbox" value="1">Food Restrictions </label-->
                                <label style="color: red;"><input id="isActive" type="checkbox" value="1" ' . $activeChecked . '>Is Active</label>
							</div>
						</div>
					</div>
					<!--div id="errorMSG"></div-->
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div id="editClient" class="btn btn-success">Save</div>&nbsp;
                            <a href="clients.php" class="btn btn-danger">Cancel</a>
						</div>
					</div>
				</form>';
    
    
    
	/*echo '<form id="editClientForm" role="form" method="post">
                <br>
				<input id="cID" type="hidden" value="'.$clientID.'">
                <div class="form-group input-group row">
				
                    <label  class="col-md-3 control-label">First Name</label>
					<div class="col-md-9">
					  <input id="fName" type="text" class="form-control" value="'.$info['cFirstName'].'" name="fName">
					</div>
					
					<label  class="col-md-3 control-label">Last Name</label>
					<div class="col-md-9">
					  <input id="lName" type="text" class="form-control" value="'.$info['cLastName'].'" >
					</div>
					
                   
                    
                </div>
                <div class="form-group input-group row">
                    <label  class="col-md-3 control-label">Email</label>
					<div class="col-md-9">
					  <input id="email" type="text" class="form-control" value="" >
					</div>
					<label  class="col-md-3 control-label">Phone#</label>
					<div class="col-md-9">
					  <input id="phone" type="text" class="form-control" value="'.$info['cPhone'].'" >
					</div>
                    
                </div>
                
                <div class="form-group input-group row">
                    <label  class="col-md-3 control-label">Address</label>
					<div class="col-md-9">
					  <input id="addr1" type="text" class="form-control" value="'.$info['cAddress1'].'" >
					</div>
					<label  class="col-md-3 control-label">Address 2</label>
					<div class="col-md-9">
					  <input id="addr2" type="text" class="form-control" value="'.$info['cAddress2'].'" >
					</div>
					<label  class="col-md-3 control-label">City</label>
					<div class="col-md-9">
					  <input id="city" type="text" class="form-control" value="'.$info['cCity'].'" >
					</div>
					<label  class="col-md-3 control-label">Zip</label>
					<div class="col-md-9">
					  <input id="zip" type="text" class="form-control" value="'.$info['cZip'].'" >
					</div>
					<label  class="col-md-3 control-label">State</label>
					<div class="col-md-9">
					  <input id="state" type="text" class="form-control" value="'.$info['cState'].'" >
					</div>
					
                   
                </div>
                <div class="form-group input-group row">
                    <label  >Delivery Notes</label>					
					  <textarea id="delNotes" class="form-control" rows="4" style="min-width: 100%">'.$info['cDeliveryNotes'].'</textarea>                    
                </div>
                <div class="checkbox row">
					<label><input id="FA" type="checkbox" value="1">Food Allergies</label>
					<label><input id="FR" type="checkbox" value="1">Food Restrictions</label>
                    <label><input id="Active" type="checkbox" value="1" checked="">Is Active</label>
                </div>
                <div id="editClient" class="btn btn-success">Edit Client</div>
            </form>';*/
}

function editDriver($driverID){
	include('../connection.php');
	$query = "SELECT *
				FROM drivers
				WHERE dID = $driverID";
	$sql = $db->query($query);
	$info = $sql->fetch_array();
	$schedule = $info['dSchedule'];
	$schedule = explode(",", $schedule);
	$MoC = (in_array("Mo", $schedule) ? "checked" : "");
	$TuC = (in_array("Tu", $schedule) ? "checked" : "");
	$WeC = (in_array("We", $schedule) ? "checked" : "");
	$ThC = (in_array("Th", $schedule) ? "checked" : "");
	$FrC = (in_array("Fr", $schedule) ? "checked" : "");
	
	echo '<form id="editDriverForm" class="form-horizontal" action="#" role="form" method="post">
					<input id="dID" type="hidden" value="'.$driverID.'">
					<div class="form-group">
						<label class="control-label col-sm-2" for="fName">First Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="fName" name="fName" value="'.$info['dFirstName'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="lName">Last Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="lName" name="lName" value="'.$info['dLastName'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Email:</label>
						<div class="col-sm-6">
							<input type="email" class="form-control" id="email" name="email" value="'.$info['dEmail'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="phone">Phone Number:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="phone" name="phone" value="'.$info['dPhoneNumber'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="dLicense">Drivers License #:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="dLicense" name="dLicense" value="'.$info['dLicenseNumber'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="vehMake">Vehicle Make:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="vehMake" name="vehMake" value="'.$info['dVehicleMake'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="vehModel">Vehicle Model:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="vehModel" name="vehModel" value="'.$info['dVehicleModel'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="vehYear">Vehicle Year:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="vehYear" name="vehYear" value="'.$info['dVehicleYear'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="vehTag">Vehicle Tag:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="vehTag" name="vehTag" value="'.$info['dVehicleTag'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="insCo">Insurance Company:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="insCo" name="insCo" value="'.$info['dInsuranceCo'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="insPolicy">Policy Number:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="insPolicy" name="insPolicy" value="'.$info['dInsurancePolicy'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="notes">Driver Notes:</label>
						<div class="col-sm-6">
							<textarea id="delNotes" name="notes" class="form-control" rows="6" style="min-width: 100%">
							'.$info['dStatusComment'].'
							</textarea>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div class="checkbox">
								<legend>Delivery Days *</legend>
								<label><input type="checkbox" name="schedule" value="Mo" '.$MoC.'>Monday</label>
								<label><input type="checkbox" name="schedule" value="Tu" '.$TuC.'>Tuesday</label>
								<label><input type="checkbox" name="schedule" value="We" '.$WeC.'>Wednesday</label>
								<label><input type="checkbox" name="schedule" value="Th" '.$ThC.'>Thursday</label>
								<label><input type="checkbox" name="schedule" value="Fr" '.$FrC.'>Friday</label>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div id="editDriver" class="btn btn-success">Save</div>
                            <a href="drivers.php" class="btn btn-danger">Cancel</a>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div id="changePassword" class="btn btn-success" onclick="changePassword('.$driverID.')">Generate New Password</div>
						</div>
					</div>

				</form>';
}

function gUsername($name, $last){
	include('../connection.php');
	if($last == "recursive"){
		$userName = $name;
	} else {
		$userName = substr($name, 0, 1);
		$userName .= $last;
	}
	
	// Check if username is taken
	$query = 'SELECT * FROM drivers where dUsername = "'.$userName.'"';

	$sql = $db->query($query);
	$taken = $sql->num_rows;

	if($taken > 0){
		$number = rand(10, 999);
		$userName .= $number;
	    return gUsername($userName, "recursive");
	} else {
	  return $userName;
	}
}

function gPassword(){
	$lArray = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	$nol = 0;
	$pass = "";
	$next = "";
	for ($i = 0; $i <= 5; $i++) {
		$nol = rand(0,1);
		
		if($nol == 0){
			$next = rand(0,9);
		} else {
			$next = $lArray[rand(0,25)];
		}
		$pass .= $next;
	}
	return $pass;
}

function slice($input, $slice) {
    $arg = explode(':', $slice);
    $start = intval($arg[0]);
    if ($start < 0) {
        $start += strlen($input);
    }
    if (count($arg) === 1) {
        return substr($input, $start, 1);
    }
    if (trim($arg[1]) === '') {
        return substr($input, $start);
    }
    $end = intval($arg[1]);
    if ($end < 0) {
        $end += strlen($input);
    }
    return substr($input, $start, $end - $start);
}
?>