<?php
function getClient($id, $count){
	if($count == "all"){
		include('../connection.php');
	$query = "SELECT cID, cFirstName, cLastName, cPhone, cActive 
				FROM clients
				ORDER BY cLastName ASC";

	$sql = $db->query($query);
	$row_cnt = $sql->num_rows;
		if ($row_cnt == 0){
			echo "<div class='msg'>No Clients Has Been Added</div>";
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
					  <input id="fName" type="text" class="form-control" placeholder="'.$info['cFirstName'].'" name="fName">
					</div>
					
					<label  class="col-md-3 control-label">Last Name</label>
					<div class="col-md-9">
					  <input id="lName" type="text" class="form-control" placeholder="'.$info['cLastName'].'" >
					</div>
					
                   
                    
                </div>
                <div class="form-group input-group row">
                    <label  class="col-md-3 control-label">Email</label>
					<div class="col-md-9">
					  <input id="email" type="text" class="form-control" placeholder="" >
					</div>
					<label  class="col-md-3 control-label">Phone#</label>
					<div class="col-md-9">
					  <input id="phone" type="text" class="form-control" placeholder="'.$info['cPhone'].'" >
					</div>
                    
                </div>
                
                <div class="form-group input-group row">
                    <label  class="col-md-3 control-label">Address</label>
					<div class="col-md-9">
					  <input id="addr1" type="text" class="form-control" placeholder="'.$info['cAddress1'].'" >
					</div>
					<label  class="col-md-3 control-label">Address 2</label>
					<div class="col-md-9">
					  <input id="addr2" type="text" class="form-control" placeholder="'.$info['cAddress2'].'" >
					</div>
					<label  class="col-md-3 control-label">City</label>
					<div class="col-md-9">
					  <input id="city" type="text" class="form-control" placeholder="'.$info['cCity'].'" >
					</div>
					<label  class="col-md-3 control-label">Zip</label>
					<div class="col-md-9">
					  <input id="zip" type="text" class="form-control" placeholder="'.$info['cZip'].'" >
					</div>
					<label  class="col-md-3 control-label">State</label>
					<div class="col-md-9">
					  <input id="state" type="text" class="form-control" placeholder="'.$info['cState'].'" >
					</div>
					
                   
                </div>
                <div class="form-group input-group row">
                    <label  >Delivery Notes</label>					
					  <textarea id="delNotes" class="form-control" rows="4" style="min-width: 100%">'.$info['cDeliveryNotes'].'</textarea>                    
                </div>
                <div class="checkbox row">
					<label><input id="FA" type="checkbox" value="" checked="">Food Allergies</label>
					<label><input id="FR" type="checkbox" value="" checked="">Food Restrictions</label>
                    <label><input id="Active" type="checkbox" value="" checked="">Is Active</label>
                </div>
				<div id="errorMSG"></div>
                <div id="editClient" class="btn btn-success">Edit Client</div>
            </form>';
}
?>