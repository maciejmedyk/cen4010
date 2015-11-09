<?php
include_once("session.php"); 
$search = $_POST['searchFor'];
$where = $_POST['where'];

if($where == "client"){
	if(strlen($search) == 0){
		getClient(0,"all");
	} else {
		searchClient($search);	
	}	
} elseif($where == "driver"){
    if(strlen($search) == 0){
		getDrivers(0,"all");
	} else {
		searchDriver($search);	
	}
}


?>