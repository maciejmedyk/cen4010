<?php
function getClient($id, $count){
	if($count == "all"){
		include('../connection.php');
	$query = "SELECT cID, cFirstName, cLastName, cPhone, cActive, cDeliveryNotes
				FROM clients
				ORDER BY cLastName ASC";

	$sql = $db->query($query);
	$row_cnt = $sql->num_rows;
		if ($row_cnt == 0){
			echo "<div class='alert alert-warning fade in msg'>There are currently no clients in the database.</div>";
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
					<td><a href='#' class='dTableButton btn btn-xs btn-success' data-driverID='" . $info['cID'] . "'>Edit</a></td>
					<td>" . $info['cID'] . "</td>
					<td>" . $info['cLastName'] . " " . $info['cFirstName'] . "</td>
					<td>" . $info['cPhone'] . "</td>
					<td>" . $active . "</td>
					<td>" . $info['cDeliveryNotes'] . "</td>
				</tr>";
				
				/*echo '<div class="strip">
						<div class="col-md-6">'.$info['cLastName'].' '.$info['cFirstName'].'</div>
						<div class="col-md-2">'.$info['cPhone'].'</div>
						<div class="col-md-2">'.$active.'</div>
						<div class="col-md-1"><div onclick="editClient('.$info['cID'].')" >Edit</div></div>
						<div class="col-md-1"><div onclick="actionClient('.$info['cID'].')">'.$action.'</div></div>
					</div>';*/
			}
			echo "</tbody></table>";
		}
	} else {
		
	}
}

function getDrivers($id, $count){
	if($count == "all"){
		include('../connection.php');
	$query = "SELECT *
				FROM drivers
				ORDER BY dLastName ASC";

	$sql = $db->query($query);
	$row_cnt = $sql->num_rows;
		if ($row_cnt == 0){
			echo "<div class='alert alert-warning fade in msg'>There are currently no drivers in the database.</div>";
		} else {
			echo "<table class='alignleft table table-hover'>
			<thead class='tableHead'>
			<tr>
			<th><i class='fa fa-check-square'></iclass></th>
			<th>ID</th>
			<th>Name</th>
			<th>Username</th>
			<th>Vehicle</th>
			<th>Vehicle Tag</th>
			<th>Insurance</th>
			<th>Insurance Policy</th>
			<th>Status</th>
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

				echo "<tr>
					<td><a href='#' class='dTableButton btn btn-xs btn-success' data-driverID='" . $info['dID'] . "'>Edit</a></td>
					<td>" . $info['dID'] . "</td>
					<td>" . $info['dLastName'] . " " . $info['dFirstName'] . "</td>
					<td>" . $info['dUsername'] . "</td>
					<td>" . $info['dVehicleYear'] . " " . $info['dVehicleMake'] . " " . $info['dVehicleModel'] . "</td>
					<td>" . $info['dVehicleTag'] . "</td>
					<td>" . $info['dInsuranceCo'] . "</td>
					<td>" . $info['dInsurancePolicy'] . "</td>
					<td>" . $info['dStatusComment'] . "</td>
				</tr>";

				#echo '<div class="strip">
				#		<div class="col-md-6">'.$info['dLastName'].' '.$info['dFirstName'].'</div>
				#		<div class="col-md-2"></div>
				#		<div class="col-md-2">'.$active.'</div>
				#		<div class="col-md-1"><div onclick="editDriver('.$info['dID'].')" >Edit</div></div>
				#		<div class="col-md-1"><div onclick="actionDriver('.$info['dID'].')">'.$action.'</div></div>
				#	</div>';
			}
			echo "</tbody></table>";
		}
	} else {
		
	}
}

function searchClient($name){
	include('../connection.php');
	$query = "SELECT cID, cFirstName, cLastName, cPhone, cActive 
				FROM clients
				WHERE cFirstName LIKE '%$name%' OR cLastName LIKE '%$name%' OR cPhone LIKE '%$name%'
				ORDER BY cLastName ASC";

	$sql = $db->query($query);
	$row_cnt = $sql->num_rows;
		if ($row_cnt == 0){
			echo "<div class='msg'>No clients can be found</div>";
		} else {
			
			while ($info = $sql->fetch_array()) {
				if($info['cActive'] == 1){
					$active ="Yes";
				} else {
					$active ="No";
				}
				echo '<div class="strip">
						<div class="col-md-6">'.$info['cLastName'].' '.$info['cFirstName'].'</div>
						<div class="col-md-2">'.$info['cPhone'].'</div>
						<div class="col-md-2">'.$active.'</div>
						<div class="col-md-1"><div onclick="editClient('.$info['cID'].')" >Edit</div></div>
						<div class="col-md-1"><div onclick="deleteClient('.$info['cID'].')">Delete</div></div>
					</div>';
			}
			
		}
	
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
	echo '<div class="formTitle">Edit Client Information</div>';
	echo '<form id="editClientForm" role="form" method="post">
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
				<div id="errorMSG"></div>
                <div id="editClient" class="btn btn-success">Edit Client</div>
            </form>';
}

function editDriver($clientID){
	include('../connection.php');
	$query = "SELECT *
				FROM drivers
				WHERE dID = $clientID";
	$sql = $db->query($query);
	$info = $sql->fetch_array();
	echo '<div class="formTitle">Edit Client Information</div>';
	echo '<form id="editClientForm" role="form" method="post">
                <br>
				<input id="cID" type="hidden" value="'.$clientID.'">
                <div class="form-group input-group row">
				
                    <label  class="col-md-3 control-label">First Name</label>
					<div class="col-md-9">
					  <input id="fName" type="text" class="form-control" value="'.$info['dFirstName'].'" name="fName">
					</div>
					
					<label  class="col-md-3 control-label">Last Name</label>
					<div class="col-md-9">
					  <input id="lName" type="text" class="form-control" value="'.$info['dLastName'].'" >
					</div>
					
                   
                    
                </div>
                <div class="form-group input-group row">
                    <label  class="col-md-3 control-label">Email</label>
					<div class="col-md-9">
					  <input id="email" type="text" class="form-control" value="" >
					</div>
					<label  class="col-md-3 control-label">Phone#</label>
					<div class="col-md-9">
					  <input id="phone" type="text" class="form-control" value="'.$info['dPhone'].'" >
					</div>
                    
                </div>
                
                <div class="form-group input-group row">
                    <label  class="col-md-3 control-label">Address</label>
					<div class="col-md-9">
					  <input id="addr1" type="text" class="form-control" value="'.$info['dAddress1'].'" >
					</div>
					<label  class="col-md-3 control-label">Address 2</label>
					<div class="col-md-9">
					  <input id="addr2" type="text" class="form-control" value="'.$info['dAddress2'].'" >
					</div>
					<label  class="col-md-3 control-label">City</label>
					<div class="col-md-9">
					  <input id="city" type="text" class="form-control" value="'.$info['dCity'].'" >
					</div>
					<label  class="col-md-3 control-label">Zip</label>
					<div class="col-md-9">
					  <input id="zip" type="text" class="form-control" value="'.$info['dZip'].'" >
					</div>
					<label  class="col-md-3 control-label">State</label>
					<div class="col-md-9">
					  <input id="state" type="text" class="form-control" value="'.$info['dState'].'" >
					</div>
					
                   
                </div>
                <div class="form-group input-group row">
                    <label  >Delivery Notes</label>					
					  <textarea id="delNotes" class="form-control" rows="4" style="min-width: 100%">'.$info['dDeliveryNotes'].'</textarea>                    
                </div>
                <div class="checkbox row">
					<label><input id="FA" type="checkbox" value="1">Food Allergies</label>
					<label><input id="FR" type="checkbox" value="1">Food Restrictions</label>
                    <label><input id="Active" type="checkbox" value="1" checked="">Is Active</label>
                </div>
				<div id="errorMSG"></div>
                <div id="editClient" class="btn btn-success">Edit Client</div>
            </form>';
}
?>