<?php
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
    echo '<form id="editClientForm" class="form-horizontal" action="#" role="form" method="post">
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
							<input type="password" class="form-control" id="pwd" name="pwd" value="'.$info['cFirstName'].'">
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
							<textarea id="delNotes" name="delNotes" class="form-control" rows="6" style="min-width: 100%"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div class="checkbox">
								<label><input id="FA" type="checkbox" value="1">Food Allergies </label>
								<label><input id="FR" type="checkbox" value="1">Food Restrictions </label>
                                <label style="color: red;"><input id="isActive" type="checkbox" value="1" ' . $activeChecked . '>Client is Active </label>
							</div>
						</div>
					</div>
					<div id="errorMSG"></div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<div id="editClient" class="btn btn-success">Save</div>
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

function getTodaysDay($d){
	if($d == null){
		$d = date("w");
	}
	switch ($d) {
    case 0:
        $dayW = "Su";
        break;
    case 1:
        $dayW = "Mo";
        break;
    case 2:
        $dayW = "Tu";
        break;
	case 3:
        $dayW = "We";
        break;
	case 4:
        $dayW = "Th";
        break;
	case 5:
        $dayW = "Fr";
        break;
	case 6:
        $dayW = "Sa";
        break;
	}
	return $dayW;
}

// FIX SUNDAY BUG
function getWeek(){
	$d = date("w");
	$w = date('W');
	if($d == 0){
		$w++;
	}
	return $w;
}

function copyLastWeek(){
	$time_pre = microtime(true);
	include('../connection.php');
	set_time_limit(120);
	$lastWeek = getWeek()-1;
	$thisWeek = getWeek();
	$query = "SELECT
			  routes.rID,
			  routes.rWeek,
			  routes.dID,
			  routes.cID,
			  routes.rDay
			FROM routes
			  INNER JOIN clients
			WHERE routes.cID = clients.cID
			AND clients.cActive = 1
			AND routes.rWeek = $lastWeek";
	//echo $query;
	$sql = $db->query($query);
	 while ($info = $sql->fetch_array()) {
		$cID = $info['cID'];
		$dID = $info['dID'];
		// Check if client is in for this week.
		if(!inForThisWeek($cID,$thisWeek,$db)){
			// Today is
			for($i = 1; $i <= 5; $i++){
				$delDay = date('Y/m/d',time()+( $i - date('w'))*24*3600);
				$rDay = getTodaysDay($i);
				
				//echo $delDay.' '.$info['cID'].' '.$rDay.' '.$thisWeek.'</br>';
				
					$unixDate = unixTime($delDay);
					$query = "INSERT INTO routes (cID,dID,rDate,unixDate,rDay,rWeek) 
								VALUES ('$cID','$dID', '$delDay','$unixDate', '$rDay', '$thisWeek')";
					//echo $query;
					$db->query($query);
				
			}
		}
	}
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	
	echo "Last weeks routes has been copyed: ".format_period($exec_time)."</br>";
}

function populateRoutes(){
	$time_pre = microtime(true);
	include('../connection.php');
	set_time_limit(120);
	$reportS = 0;
	$reportW = 0;
	$thisWeek = getWeek();
	$query = "SELECT cID FROM clients WHERE cActive = 1";
	$sql = $db->query($query);
	 while ($info = $sql->fetch_array()) {
		$cID = $info['cID'];
		// Check if client is in for this week.
		if(!inForThisWeek($cID,$thisWeek,$db)){
			// Today is
			for($i = 1; $i <= 5; $i++){
				$delDay = date('Y/m/d',time()+( $i - date('w'))*24*3600);
				$rDay = getTodaysDay($i);
				
				//echo $delDay.' '.$info['cID'].' '.$rDay.' '.$thisWeek.'</br>';
				
				$unixDate = unixTime($delDay);
			
					$query = "INSERT INTO routes (cID,rDate,unixDate,rDay,rWeek) 
								VALUES ('$cID', '$delDay','$unixDate', '$rDay', '$thisWeek')";
					//echo $query;
					$db->query($query);
					$reportS++;
				
			}
		} else {
			$reportW++;
		}
		
	}

	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	echo "$reportS Clients were populated -- $reportW Clients were already  populated for this week</br>";
	echo "Routes has been populated: ".format_period($exec_time)."</br>";
	
	
}
function insertDriver($date){
	$time_pre = microtime(true);
	include('../connection.php');
	set_time_limit(120);
	$query = "SELECT dID, lat, lng,dSchedule FROM drivers WHERE dActive = 1";
	$sql = $db->query($query);
	while( $dInfo = $sql->fetch_array()){
		$driver_array[] = $dInfo;
	}
	if($date == 0){
		$rQuery = "SELECT
			  routes.rID,
			  routes.cID,
			  routes.rDay,
			  clients.cLng,
			  clients.cLat
			FROM routes
			  INNER JOIN clients
			WHERE routes.cID = clients.cID";
	} else {
		$todayUnixDate = mktime(6, 0, 0, date('n'), date('j'), date('Y'));
		//echo $todayUnixDate;
		$rQuery = "SELECT
			  routes.rID,
			  routes.cID,
			  routes.rDay,
			  clients.cLng,
			  clients.cLat
			FROM routes
			  INNER JOIN clients
			WHERE routes.unixDate = $todayUnixDate AND  routes.cID = clients.cID";
	}
	//echo $rQuery;
	$route = $db->query($rQuery);
	while($cInfo = $route->fetch_array()){
		//echo "----------------------------------</br>";
		//echo $cInfo['rID'].' '.$cInfo['cID'].' '.$cInfo['rDay'].' '.$cInfo['cLat'].' '.$cInfo['cLng'].'</br>';
		$driver = closestDriver($cInfo['cLat'],$cInfo['cLng'], $cInfo['rDay'], $driver_array);
		
		$rID = $cInfo['rID'];
		$updateQ = "UPDATE routes SET dID =$driver WHERE rID=$rID";
		//echo $updateQ.'</br>';
		$db->query($updateQ);
		//echo "----------------------------------</br>";
	}
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	
	echo "Drivers has been assigned: ".format_period($exec_time)."</br>";
}

function closestDriver($cLat,$cLng, $dDay, &$dArray){
	if($cLat == null || $cLng == null){
		//echo "Invalid location </br>";
		return 0;
	} else {
		$closestDriver = 0;
		$sDistance = 6371000;
		$cDistance = 6371000;
		//echo $cLat.' '.$cLng.'</br>';
		foreach ($dArray as $key => $driver) {
			$driverID = $driver[0];
			$driverLat = $driver[1];
			$driverLng = $driver[2];
			$driverSchedule = $driver[3];
			$driverScheduleArray = explode(",",$driverSchedule);
			// Check delivery Day
			if(in_array($dDay, $driverScheduleArray)){
				//echo $driverID.' ';
				$cDistance = vincentyGreatCircleDistance($cLat,$cLng,$driverLat,$driverLng);
				if($cDistance < $sDistance){
					$closestDriver = $driverID;
					$sDistance = $cDistance;
				}
			} else {
				//echo "($driverID) I dont work that day ($driverSchedule | $dDay | ) </br>";
			}
			//echo $sDistance.' '.$cDistance.'</br>';
		}
		//echo $closestDriver.'</br>';
		return $closestDriver;
	}
}

function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}

function inForThisWeek($cID, $thisWeek,$db){

	$query = "SELECT * FROM routes where cID = $cID AND rWeek = '$thisWeek'";


	$sql = $db->query($query);
	$iWeek = $sql->num_rows;

	if($iWeek > 0){
		return true;
	} else {
	  return false;
	}
}
function initRoutes(){
	$time_pre = microtime(true);
	// Populate the routes DB
	populateRoutes();
	// Select Driver
	insertDriver(0);
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	
	echo "New Schedules as Been Created: ".format_period($exec_time)."</br>";
	
	
}


function getDeliverys($weekNumber, $dDay,$d){
	include('../connection.php');
	$listOfAllDrivers = genDList($db);
	$rQuery = "SELECT
				  clients.cFirstName,
				  clients.cLastName,
				  drivers.dID,
				  drivers.dFirstName,
				  drivers.dLastName,
				  drivers.dPhoneNumber,
				  routes.rID,
				  routes.rDate,
				  routes.rSuccess,
				  routes.rReschedule
				FROM routes
				  INNER JOIN clients
				  INNER JOIN drivers
				WHERE clients.cID = routes.cID
				AND drivers.dID = routes.dID
				AND routes.rWeek = $weekNumber
				AND routes.rDay = '$dDay'";
	//echo $rQuery;
	$route = $db->query($rQuery);
	$delCount = $route->num_rows;
	echo '<div>'.date("F j, Y", time() +$d).'</div>';
	echo "<table class='alignleft table table-hover'>
            <thead class='tableHead'>
            <tr>
				<th>Driver</th>
				<th>Driver Number</th>
				<th>Client</th>
				<th>Status</th>
            </tr>
            </thead>
            <tbody>";

	if($delCount == 0){
		echo "<tr>
				<th>No delivery today!</th>
			  </tr>";
	} else {
		while($dInfo = $route->fetch_array()){
			if($dInfo['rSuccess'] == 1){
				$status ="Delivered";
				#$action = "Deactivate";
			} else {
				$status ="Enroute";
				#$action = "Activate";
			}
			$rID = $dInfo['rID'];
			$dID = $dInfo['dID'];
			$clientName = $dInfo['cLastName'].' '.$dInfo['cFirstName'];
			$driverName = $dInfo['dLastName'].' '.$dInfo['dFirstName'];
			$driverNumber = $dInfo['dPhoneNumber'];
			
			/*if($dDay == getTodaysDay(date('w'))){
				$selectD = "<select onchange='changeDriver($rID,this.options[this.selectedIndex].value)'>
							<option value='$dID'>$driverName</option>				
							$listOfAllDrivers
							</select>";
			} else {
				$selectD = $driverName;
			}*/
			
			$selectD = "<select onchange='changeDriver($rID,this.options[this.selectedIndex].value)'>
							<option value='$dID'>$driverName</option>				
							$listOfAllDrivers
							</select>";
			
			echo "<tr>
					<td>
					$selectD
					</td>
					<td>$driverNumber</td>
					<td>$clientName</td>
					<td>$status</td>
				</tr>";
		}
	}
	echo "</tbody></table>";

}

function genDList($db){
	
	$query = "SELECT dID,dLastName,dFirstName FROM drivers WHERE dActive = 1 ORDER BY dLastName ASC";

    $sql = $db->query($query);
    $list = '';
    while ($info = $sql->fetch_array()) {
		$dID = $info['dID'];
		$driverName = $info['dLastName'].' '.$info['dFirstName'];
		$list .= '<option value="'.$dID.'">'.$driverName.'</option>';
	}
	return $list;
}

function rangeWeek($datestr) {
	$dt = strtotime($datestr);
	echo date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
	echo " - ";
	echo date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
}

function unixTime($thisDate){
	//echo $thisDate."</br>";
	$xDate = explode("/", $thisDate);
	return mktime(6, 0, 0, $xDate[1], $xDate[2], $xDate[0]);
}

function format_period($seconds_input){
  $hours = (int)($minutes = (int)($seconds = (int)($milliseconds = (int)($seconds_input * 1000)) / 1000) / 60) / 60;
  return $hours.':'.($minutes%60).':'.($seconds%60).(($milliseconds===0)?'':'.'.rtrim($milliseconds%1000, '0'));
}
?>