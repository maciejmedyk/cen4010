<?php
//Used to log php info to the javascript console.
function consoleLog($msg){
    echo "<script>console.log('$msg');</script>";
}

//Redirect to different page.
function Redirect($url, $permanent = false){
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

//
//Search functions just forward the request in the appropriate manner.
//
function searchClient($name){
    getClient($name, "search");
}

function searchAdmin($name){
    getAdminTable($name, "search");
}

function searchDriver($name){
    getDrivers($name, "search");
}

function searchReports($string){
    getReportsTable($string, "search");
}

function searchDeliveries($string){
    getDeliveriesTable($string, "search");

}

function searchEmergencies($search){
    getEmergencyTable($search, "search");
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

//
//Gets a form to add or edit an administrator.
//use $adminID of -1 for an empty form.
//
function getAdminForm($adminID){
    
    if ($_SESSION["isSuperAdmin"] != 1){
        echo '
        <div class="jumbotron">
            <h1>Sorry! :(</h1>
            <p>You must be a Super Administrator to add an administrator.</p>
        </div>
        ';
        return;
    }
    
    if($adminID === -1){
        $emptyForm = true;
        $sID = "";
    }else{
        $emptyForm = false;
        $sID = 'value="'.$adminID.'"';
    }
    
    if(!$emptyForm){
        include('../connection.php');
        $query = "SELECT *
                    FROM superusers
                    WHERE sID = $adminID
                    ORDER BY sLastName ASC";
        $sql = $db->query($query);
        $info = $sql->fetch_array();

        $activeChecked = ($info['sActive'] == 1)? "checked" : "";
        $superChecked = ($info['sSuperAdmin'] == 1)? "checked" : "";
    }

    echo '          <br /><div class="">
                    <form id="editAdminForm" class="form-horizontal" action="#" role="form" method="post">
                    <input type="text" id="sID" '.$sID.' hidden>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="fName">First Name:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="fName" name="fName" placeholder="Enter first name" value="' . ((isset($info['sFirstName']))? $info['sFirstName'] : "") . '">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="lName">Last Name:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="lName" name="lName" placeholder="Enter last name" value="' . ((isset($info['sLastName']))? $info['sLastName'] : "") . '">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Email:</label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="' . ((isset($info['sUsername']))? $info['sUsername'] : "") . '">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">Password:</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter a password." value="' . ((isset($info['sPassword']))? $info['sPassword'] : "") . '">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd2">Verify Password:</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="pwd2" name="pwd2" placeholder="Re-Enter your password for verification." value="' . ((isset($info['sPassword']))? $info['sPassword'] : "") . '">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="securityQuestion">Security Question:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="securityQuestion" name="securityQuestion" placeholder="Enter a question you can answer during password recovery." value="' . ((isset($info['sSecurityQuestion']))? $info['sSecurityQuestion'] : "") . '">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="securityAnswer">Answer:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="securityAnswer" name="securityAnswer" placeholder="Enter your answer to the security question" value="' . ((isset($info['sSecuryAnswer']))? $info['sSecuryAnswer'] : "") . '">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-6">
                                <div class="checkbox">
                                    <label style="color: red;" ><input id="activeCheck" type="checkbox" ' . ((!$emptyForm)? $activeChecked : "checked") . ' value="1">Is Active</label>
                                </div>
                                <div class="checkbox">
                                    <label style="color: red;"><input id="superAdminCheck" type="checkbox" ' . ((!$emptyForm)? $superChecked : "") .' value="1">Is SuperAdmin</label>
                                </div>                 
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-6">
                                ' . (($emptyForm)? '<div id="addAdminButton" class="btn btn-success">Add Admin</div>' :
                                     '<div id="addAdminButton" class="btn btn-success">Save</div><a href="account.php" style="margin-left: 1em;" id="cancelAdminButton" class="btn btn-danger">Cancel</a>') . '
                            </div>
                        </div>
                    </form></div>
        ';

}

//
//Gets the administrators table populated with data.
//
function getAdminTable($id, $count){
    include('../connection.php');
	if($count == "all"){
        $query = "SELECT sID, sFirstName, sLastName, sUsername, sSuperAdmin, sActive
				FROM superusers
				ORDER BY sLastName ASC";
    }elseif($count == "search"){
        $query = "SELECT sID, sFirstName, sLastName, sUsername, sSuperAdmin, sActive 
            FROM superusers
            WHERE sFirstName LIKE '%$id%' OR sLastName LIKE '%$id%' OR sUsername LIKE '%$id%'
            ORDER BY sLastName ASC";
    }
    
    $errorMSG = "";
    
	$sql = $db->query($query);
	$row_cnt = $sql->num_rows;
    if ($row_cnt == 0){
        if ($count == "all") echo "<div class='alert alert-warning fade in msg'>There are currently no clients in the database.</div>";
        if ($count == "search") echo "<div class='alert alert-warning fade in msg'>There are currently no clients that match that query.</div>";
    } else {
        echo "<table class='alignleft table table-hover'>
            <thead class='tableHead'>
                <tr>";
        
        if ($_SESSION["isSuperAdmin"] == 1){
            echo "<th><i class='fa fa-check-square'></iclass></th>";
        }
        
        echo "      <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>";


        while ($info = $sql->fetch_array()) {
            
            if($info['sActive'] == 1){
                $status ="Active";
            } else {
                $status ="Inactive";
            }
            
            if($info['sSuperAdmin'] == 1){
                $type ="Super";
            } else {
                $type ="Regular";
            }
            
            if ($_SESSION["isSuperAdmin"] == 1){
                echo "<tr><td><a href='accountEdit.php?sID=".$info['sID']."' class='sTableButton btn btn-xs btn-success' data-adminID='" . $info['sID'] . "'>Edit</a></td>";
            }else{
                echo "<tr>";
            }
            
            echo "<td>" . $info['sID'] . "</td>
                <td>" . $info['sLastName'] . " " . $info['sFirstName'] . "</td>
                <td>" . $info['sUsername'] . "</td>
                <td>" . $type . "</td>
                <td>" . $status . "</td>
            </tr>";
        }
        echo "</tbody></table>";
    }
}

//
//Gets the settings form.
//
function getSettingsForm(){
    
}

//
//
//
function getClientNotesTable($id, $count){
    
}

//
//Gets a table of all the emergency notifications sent to the server.
//
function getEmergencyTable($id, $count){
        include('../connection.php');
	if($count == "all"){
        $query = "SELECT emergency.*, drivers.dFirstName, drivers.dLastName
                FROM emergency, drivers
                WHERE emergency.dID = drivers.dID
                ORDER BY eDate DESC;";
    }elseif($count == "search"){
        //This query needs fixing.
        $query = "SELECT emergency.*, drivers.dFirstName, drivers.dLastName
            FROM emergency, drivers
            WHERE eID LIKE '%$id%' OR eDate LIKE '%$id%' OR dFirstName LIKE '%$id%' OR dLastName LIKE '%$id%'
            ORDER BY eDate DESC;";
    }else{
        $query = "SELECT emergency.*, drivers.dFirstName, drivers.dLastName
                FROM emergency, drivers
                WHERE emergency.dID = drivers.dID
                ORDER BY eDate DESC
                LIMIT ".$count.";";
    }

    $sql = $db->query($query);
    $row_cnt = $sql->num_rows;
    if ($row_cnt == 0){
        print_r( $db->error_list );
        if ($count == "all") echo "<div class='alert alert-warning fade in msg'>There are currently no emergency events posted.</div>";
        if ($count == "search") echo "<div class='alert alert-warning fade in msg'>There are currently no events that match your query.</div>";
    } else {
        echo "<div class=''><table id='emergencyTable' class='alignleft table table-hover'>
        <thead class='tableHead'>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Submitted By</th>
            <th>Location</th>
            <th>Resolved</th>
            <th>Note</th>
        </tr>
        </thead>
        <tbody>";

        while ($info = $sql->fetch_array()) {
            
            if($info['eCoordinates'] != ""){
                
                //This converts the coordinates to an address which can be displayed in the chart
                /*$json = "";
                $coords = str_replace(' ', '', $info['eCoordinates']);
                $search =  "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coords; 
                $json = file_get_contents($search);
                if($json != ""){
                    $json = json_decode($json);
                    $location = $json->{'results'}[1]->{'formatted_address'};
                }*/
                
                $array = explode(' ', $info['eCoordinates'], 2);
                $location = "{lat: ".$array[0]." lng: ".$array[1]."}";
                
            }else{
                $location = "";
            }
            
            echo "<tr data-coords='" . $info['eCoordinates'] . "' data-eID='" . $info['eID'] . "'>
                </td>
                <td>" . $info['eID'] . "</td>
                <td>" . $info['eDate'] . "</td>
                <td>" . $info['dLastName'] . " " . $info['dFirstName'] . "</td>
                <td><a href=# onclick='replaceMarker($location)'  >".(($location != '')? 'Show on map' : 'No Data')."</a></td>
                <td>" . $info['eResolved'] . "</td>
                <td>Notes?</td>
            </tr>";
        }
        echo "</tbody></table></div>";
    }
}

//
//Gets a table of all the notes attached to clients.
//
function getNotesTable($id, $count){
        include('../connection.php');
	if($count == "all"){
        $query = "SELECT notes.*, clients.cFirstName, clients.cLastName
                FROM notes, clients
                WHERE notes.cID = clients.cID
                ORDER BY nDate DESC";
    }elseif($count == "search"){
        //This query needs fixing.
        $query = "SELECT notes.*, clients.cFirstName, clients.cLastName
            FROM notes, clients
            WHERE notes.nID LIKE '%$id%' OR nDate LIKE '%$id%' OR cFirstName LIKE '%$id%' OR cLastName LIKE '%$id%'
            ORDER BY nDate DESC";
    }else{
        $query = "SELECT notes.*, clients.cFirstName, clients.cLastName
                FROM notes, clients
                WHERE notes.cID = clients.cID
                ORDER BY nDate DESC
                LIMIT ".$count.";";
    }

    $sql = $db->query($query);
    $row_cnt = $sql->num_rows;
    if ($row_cnt == 0){
        print_r( $db->error_list );
        if ($count == "all") echo "<div class='alert alert-warning fade in msg'>There are currently no notes posted.</div>";
        if ($count == "search") echo "<div class='alert alert-warning fade in msg'>There are currently no notes that match your query.</div>";
    } else {
        echo "<table id='notesTable' class='scrollable-y alignleft table table-hover'>
        <thead class='tableHead'>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Client</th>
            <th>Note</th>
            <th>Urgent</th>
        </tr>
        </thead>
        <tbody class='scrollable-y'>";

        while ($info = $sql->fetch_array()) {
            
            echo "<tr style='" . (($info['nUrgent'] == 1)? 'background-color: #FFEAEA;' : '' ) . "' data=nID'" . $info['nID'] . "' data-cID='" . $info['cID'] . "'>
                </td>
                <td>" . $info['nID'] . "</td>
                <td>" . $info['nDate'] . "</td>
                <td>" . $info['cLastName'] . " " . $info['cFirstName'] . "</td>
                <td>" . $info['nComment'] . "</td>
                <td>" . (($info['nUrgent'] == 1)? 'true' : 'false') . "</td>
            </tr>";
        }
        echo "</tbody></table>";
    }
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