// Search Function

$( "#search" ).keyup(function() {
	var searchT = $("#search").val();
	var type = $("#type").val();
	var where = $("#searchIN").val();
	$.ajax({
		method: "POST",
		url: "search.php",
		data: { searchFor: searchT , where: where}
	}).done(function( page ) {
		$("#displayData").html(page);
		
	});
});

//errorType = int 0 => fail, 1 => success
function errorMSG(errorString, errorType){
	console.log(errorString);
	$( "#errorMSG" ).html( errorString );
}

$("#driverForm").click(function(){
	var userName = $("#username").val();
	var password = $("#password").val();
	if(userName == "" || password == ""){
		errorMSG("Username or Password is invalid.",0);
	} else {
		$.ajax({
			method: "POST",
			url: "scripts/login.php",
			data: { userName: userName, password: password, userType: "Driver" }
		}).done(function( msg ) {
			if(msg == 0){
				errorMSG("Loged in.",0);
				window.location.href = "/driver/index.php";
			} else {
				errorMSG(msg,1);
			}
		});
	}
});

$("#adminForm").click(function(){
	var userName = $("#username").val();
	var password = $("#password").val();
	if(userName == "" || password == ""){
		errorMSG("Username or Password is invalid.",0);
	} else {
		$.ajax({
			method: "POST",
			url: "scripts/login.php",
			data: { userName: userName, password: password, userType: "Admin" }
		}).done(function( msg ) {
			if(msg == 0){
				errorMSG("Loged in.",0);
				window.location.href = "/admin/index.php";
			} else {
				errorMSG(msg,1);
			}
		});
	}
});


/*
#########################################
############ ADMIN FUNCTIONS ############
#########################################
*/
// Get client edit Form
function editClient(cID){
	$(".mask").css({"display":"block"});
	
	$.ajax({
			method: "POST",
			url: "edit.php",
			data: { action:"clientEdit",cID: cID }
		}).done(function( page ) {
			$(".popUp").html(page);
		});
}

/*Submit Client Edit*/
$(document).on('click','#editClient',function(){
	var cID = $("#cID").val();
	var fName = $("#fName").val();
	var lName = $("#lName").val();
	var email = $("#email").val();
	var phone = $("#phone").val();
	var addr1 = $("#addr1").val();
	var addr2 = $("#addr2").val();
	var city = $("#city").val();
	var state = $("#state").val();
	var zip = $("#zip").val();
	var delNotes = $("#delNotes").val();
	var FA = $("#FA").val();
	var FR = $("#FR").val();
	var Active = $("#Active").val();
	var MSG = "";
	
	if(textValidate(fName) || textValidate(lName) || phoneValidate(phone) || emailValidate(email) || textValidate(addr1) || textValidate(city) || textValidate(state) || textValidate(zip)){
		
		$.ajax({
			method: "POST",
			url: "edit.php",
			data: { action:"submitClientEdit",cID: cID }
		}).done(function( page ) {
			$(".popUp").html(page);
		});
		
	} else {
		MSG += "Please Fill in all required fields.</br>";
	} 
	if(!emailValidate(email)){
		MSG += "Please Enter a Valid Email.</br>";
	}
	if(!phoneValidate(phone)){
		MSG += "Please Enter a Valid Phone Number.</br>";
	}
	
	errorMSG(MSG, 0);
});

/*Delete Client*/
function deleteClient(cID){
	console.log("delete"+cID);
}


// Login Forms
$("#driverLog").click(function(){
	$( ".admin_L" ).addClass( "disable" );
	$( ".driver_L" ).removeClass( "disable" );
});

$("#adminLog").click(function(){
	$( ".admin_L" ).removeClass( "disable" );
	$( ".driver_L" ).addClass( "disable" );
});


/*Close POPUP*/
$(".popClose").click(function(){
	$(".mask").css({"display":"none"});
});


/*
##########################################
########## VALIDATION FUNCTIONS ##########
##########################################
*/

/*Validate Text*/
function textValidate(text){
	return true;
}
/*Validate Email*/
function emailValidate(text){
	return true;
}
/*Validate Phone Number*/
function phoneValidate(text){
	return true;
}