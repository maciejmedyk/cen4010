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
			url: "clientHelper.php",
			data: { action:"clientEdit",cID: cID }
		}).done(function( page ) {
			$(".popUp").html(page);
		});
}

// Get client edit Form
function editDriver(cID){
	$(".mask").css({"display":"block"});
	
	$.ajax({
			method: "POST",
			url: "driverHelper.php",
			data: { action:"driverEdit",cID: cID }
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
	var FA = $("#FA").is(':checked');
	var FR = $("#FR").is(':checked');
	var Active = $("#Active").is(':checked');
	var MSG = "";
	
	if(textValidate(fName) || textValidate(lName) || phoneValidate(phone) || emailValidate(email) || textValidate(addr1) || textValidate(city) || textValidate(state) || textValidate(zip)){
		
		$.ajax({
			method: "POST",
			url: "clientHelper.php",
			data: { action:"submitClientEdit",cID: cID, fName: fName, lName: lName, email: email, phone: phone, addr1: addr1, addr2: addr2, city:  city, state: state, zip: zip, delNotes: delNotes, FA:FA, FR:FR, Active:Active }
		}).done(function( page ) {
			errorMSG(page, 0);
		});
		
	} else {
		MSG += "Please Fill in all required fields.</br>";
		if(!emailValidate(email)){
			MSG += "Please Enter a Valid Email.</br>";
		}
		if(!phoneValidate(phone)){
			MSG += "Please Enter a Valid Phone Number.</br>";
		}
		
		errorMSG(MSG, 0);
	} 
	
});

/*Submit New Client*/
$(document).on('click','#addClient',function(){
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
	var FA = $("#FA").is(':checked');
	var FR = $("#FR").is(':checked');
	var Active = 1;
	var MSG = "";
	
	if(textValidate(fName) || textValidate(lName) || phoneValidate(phone) || emailValidate(email) || textValidate(addr1) || textValidate(city) || textValidate(state) || textValidate(zip)){
		
		$.ajax({
			method: "POST",
			url: "clientHelper.php",
			data: { action:"submitNewClient",cID: cID, fName: fName, lName: lName, email: email, phone: phone, addr1: addr1, addr2: addr2, city:  city, state: state, zip: zip, delNotes: delNotes, FA:FA, FR:FR, Active:Active }
		}).done(function( page ) {
			errorMSG(page, 0);
		});
		
	} else {
		MSG += "Please Fill in all required fields.</br>";
		if(!emailValidate(email)){
			MSG += "Please Enter a Valid Email.</br>";
		}
		if(!phoneValidate(phone)){
			MSG += "Please Enter a Valid Phone Number.</br>";
		}
		
		errorMSG(MSG, 0);
	} 
	
});

/*Delete Client*/
function actionClient(cID, step){
	$(".mask").css({"display":"block"});
	
	if(step == 1){
		
		$.ajax({
				method: "POST",
				url: "clientHelper.php",
				data: { action:"clientDeleteConfirm",cID: cID }
			}).done(function( page ) {
				$(".popUp").html(page);
			});
			
	} else if(step == 0){
		$(".mask").css({"display":"none"});
	} else {
			
		$.ajax({
				method: "POST",
				url: "clientHelper.php",
				data: { action:"clientDelete",cID: cID }
			}).done(function( page ) {
				$(".popUp").html(page);
			});
	}
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


/*
##########################################
############# TABS FUNCTIONS #############
##########################################
*/


$( document ).ready(function() {
	
	init(); 
});
var tabLinks = new Array();
var contentDivs = new Array();
function init() {

  // Grab the tab links and content divs from the page
  var tabListItems = document.getElementById('tabs').childNodes;
  for ( var i = 0; i < tabListItems.length; i++ ) {
	if ( tabListItems[i].nodeName == "LI" ) {
	  var tabLink = getFirstChildWithTagName( tabListItems[i], 'A' );
	  var id = getHash( tabLink.getAttribute('href') );
	  tabLinks[id] = tabLink;
	  contentDivs[id] = document.getElementById( id );
	}
  }

  // Assign onclick events to the tab links, and
  // highlight the first tab
  var i = 0;

  for ( var id in tabLinks ) {
	tabLinks[id].onclick = showTab;
	tabLinks[id].onfocus = function() { this.blur() };
	if ( i == 0 ) tabLinks[id].className = 'selected';
	i++;
  }

  // Hide all content divs except the first
  var i = 0;

  for ( var id in contentDivs ) {
	if ( i != 0 ) contentDivs[id].className = 'tabContent hide';
	i++;
  }
}

function showTab() {
  var selectedId = getHash( this.getAttribute('href') );

  // Highlight the selected tab, and dim all others.
  // Also show the selected content div, and hide all others.
  for ( var id in contentDivs ) {
	if ( id == selectedId ) {
	  tabLinks[id].className = 'selected';
	  contentDivs[id].className = 'tabContent';
	} else {
	  tabLinks[id].className = '';
	  contentDivs[id].className = 'tabContent hide';
	}
  }

  // Stop the browser following the link
  return false;
}

function getFirstChildWithTagName( element, tagName ) {
  for ( var i = 0; i < element.childNodes.length; i++ ) {
	if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
  }
}

function getHash( url ) {
  var hashPos = url.lastIndexOf ( '#' );
  return url.substring( hashPos + 1 );
}


    