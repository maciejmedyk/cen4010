<?php
function isLocked($uID, $uType, $lockedAt){
	if($lockedAt != 0){
		include('../connection.php');
		$query = "select lockCount from trap where lockedID='$uID' AND lockType='$uType'";	
		$sql = $db->query($query);
		$row = $sql->fetch_array();
		$row_cnt = $sql->num_rows;
		
		if ($row_cnt == 1){
			if($row['lockCount'] >= $lockedAt){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
	
}

function inLockTable($uID, $uType){
	include('../connection.php');
		$query = "select * from trap where lockedID='$uID' AND lockType='$uType'";	
		$sql = $db->query($query);
		$row = $sql->fetch_array();
		$row_cnt = $sql->num_rows;
		if ($row_cnt == 1){
			return true;
		} else {
			return false;
		}
}

function bruteForceProtection($userName, $uType){
	include('../connection.php');
	$unixTime = mktime( date("H") ,  date("i") ,  date("s") , date('n'), date('j'), date('Y'));

	if($uType == 1){
		$query = "SELECT dID
						FROM drivers
						WHERE dUsername = '$userName'";
	} else {
		
	}
			
		$sql = $db->query($query);
		$row = $sql->fetch_array();
		$uID = $row['0'];
		if(inLockTable($uID, $uType)){
			// Update
			$query = "UPDATE trap SET 
				lockCount = lockCount + 1, 
				tTimestamp ='$unixTime'
				WHERE lockedID ='$uID';
                ";
		} else {
			// Insert
			$query = "INSERT INTO trap (lockedID,lockCount,lockType) 
							VALUES ('$uID', '1', '$uType')";
		}
		
		//echo $query;
		$db->query($query);
	
}

function bruteForceClean($uID, $uType){
	include('../connection.php');
	if(inLockTable($uID, $uType)){
		// Update
		$query = "DELETE FROM trap
					WHERE lockedID ='$uID' AND lockType='$uType'";
		$db->query($query);
	}
}
?>