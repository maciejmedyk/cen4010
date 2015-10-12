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
		
} else if($where == "driver"){
	
}


?>