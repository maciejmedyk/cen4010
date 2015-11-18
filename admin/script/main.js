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
	//$(".mask").css({"display":"block"});
	//$( "#errorMSG" ).html( errorString );
    
    //Im using this with bootstrap alerts till you work out what you are doing with that ugly popup :P
    $( "#errorMSG" ).html( errorString );
    if(errorType == 0){
        $( "#errorMSG" ).addClass("alert-danger").fadeIn();
    }else{
        $( "#errorMSG" ).addClass("alert-success").fadeIn();
    }
}


//Filters the drivers list according to if the driver is active.
$(document).ready(function() {
   
    $("#showInactiveDriver").click(function() {
        if ($("#showInactiveDriver").is(":checked")){
            $('#driverTable > tbody > tr').each(function() {
                $(this).removeClass( "hidden" );
            });  
        }else{
            $('#driverTable > tbody > tr').each(function() {
                if ( $(this).data('status') == "Retired"){
                    $(this).addClass( "hidden" );
                }
            });  
        }
    });
    
    $("#showInactiveClients").click(function() {
        if ($("#showInactiveClients").is(":checked")){
            $('#clientTable > tbody > tr').each(function() {
                $(this).removeClass( "hidden" );
            });  
        }else{
            $('#clientTable > tbody > tr').each(function() {
                if ( $(this).data('status') == "Retired"){
                    $(this).addClass( "hidden" );
                }
            });  
        }
    });
    
});


/*
#########################################
############ ADMIN FUNCTIONS ############
#########################################
*/
// Get client edit Form
function editClient(cID){
	if($("#showEdit"+cID).is(':visible')){
		$("#showEditDiv"+cID).animate({height:"0px"},400,function(){
			$("#showEdit"+cID).css({"display":"none"});
		});
	} else {
		$("#showEdit"+cID).css({"display":"block"});
		$("#showEditDiv"+cID).animate({height:"600px"},400);
	}
	
	
	/*$.ajax({
			method: "POST",
			url: "clientHelper.php",
			data: { action:"clientEdit",cID: cID }
		}).done(function( page ) {
			$(".popUp").html(page);
		});*/
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
        alert(page);
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
	//var FA = $("#FA").is(':checked');
	//var FR = $("#FR").is(':checked');
    var FAList = $("#FAList").val();
	var FRList = $("#FRList").val();
	var Active = $("#isActive").is(':checked');

    //Set the alergies checkbox data if an alergy is typed into the box.
    if(FAList == ""){
        FA = false;   
    }else{
        FA = true;
    }
    //Set the food restrictions checkbox data if a restriction is typed into the box.
    if(FAList == ""){
        FR = false;   
    }else{
        FR = true;
    }
    
	var MSG = "";
	console.log("Edit - "+ fName);
	if(textValidate(fName) && textValidate(lName) && phoneValidate(phone) && /*emailValidate(email) &&*/ textValidate(addr1) && textValidate(city) && textValidate(state) && textValidate(zip)){
		
		$.ajax({
			method: "POST",
			url: "clientHelper.php",
			data: { action:"submitClientEdit",cID: cID, fName: fName, lName: lName, email: email, phone: phone, addr1: addr1, addr2: addr2, city:  city, state: state, zip: zip, delNotes: delNotes, FA:FA, FR:FR, FAList:FAList, FRList:FRList, Active:Active }
		}).done(function( page ) {
			//$("showEditDiv"+cID).html("Client Info has been updated"+page);
			errorMSG(page, 1);
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

/*Submit New Drivers*/
$(document).on('click','#addDriver',function(){
	//console.log("Submit New Drivers");
	
	//var str = $("#editDriverForm").serialize();
	var cID = $("#cID").val();
	var fName = $("#fName").val();
	var lName = $("#lName").val();
	var email = $("#email").val();
	var phone = $("#phone").val();
	var license = $("#dLicense").val();
	var make = $("#vehMake").val();
	var model = $("#vehModel").val();
	var year = $("#vehYear").val();
	var tag = $("#vehTag").val();
	var insurance = $("#insCo").val();
	var policyNumber = $("#insPolicy").val();
	var delNotes = $("#delNotes").val();
	var Active = 1;
	var MSG = "";

	var schedule = [];
	 $.each($("input[name='schedule']:checked"), function(){            
                schedule.push($(this).val());
            });
		//console.log("add to array " + schedule);
	
	if(textValidate(fName) && textValidate(lName) && phoneValidate(phone) && emailValidate(email) && textValidate(schedule)){
		
		$.ajax({
			method: "POST",
			url: "driverHelper.php",
			data: { action:"submitNewDriver",
					cID: cID, 
					fName: fName, 
					lName: lName, 
					email: email, 
					phone: phone, 
					license: license, 
					make: make, 
					model:  model, 
					year: year, 
					tag: tag,
					insurance: insurance, 
					policyNumber: policyNumber, 
					delNotes: delNotes,
					schedule: schedule,
					Active:Active }
		}).done(function( page ) {
			errorMSG(page, 0);
			$("#editDriverForm").find("input[type=text], input[type=email], textarea").val("").removeAttr('checked');
			$("#editDriverForm").find("input[name='schedule']").removeAttr("checked");
			
			
		});
		
	} else {
		MSG += "Please Fill in all required (*) fields.</br>";
		errorMSG(MSG, 0);
	}
	
});

/*Submit Driver Edit*/
$(document).on('click','#editDriver',function(){
	var dID = $("#dID").val();
	var fName = $("#fName").val();
	var lName = $("#lName").val();
	var email = $("#email").val();
	var phone = $("#phone").val();
	var license = $("#dLicense").val();
	var make = $("#vehMake").val();
	var model = $("#vehModel").val();
	var year = $("#vehYear").val();
	var tag = $("#vehTag").val();
	var insurance = $("#insCo").val();
	var policyNumber = $("#insPolicy").val();
	var delNotes = $("#delNotes").val();
	var schedule = [];
	 $.each($("input[name='schedule']:checked"), function(){            
                schedule.push($(this).val());
            });
	var MSG = "";

	
	if(textValidate(fName) && textValidate(lName) && phoneValidate(phone) && emailValidate(email)){
		
		$.ajax({
			method: "POST",
			url: "driverHelper.php",
			data: { action:"submitDriverEdit",
					dID: dID, 
					fName: fName, 
					lName: lName, 
					email: email, 
					phone: phone, 
					license: license, 
					make: make, 
					model:  model, 
					year: year, 
					tag: tag,
					insurance: insurance, 
					policyNumber: policyNumber,
					schedule: schedule,
					delNotes: delNotes }
		}).done(function( page ) {
			//$("showEditDiv"+cID).html("Client Info has been updated"+page);
			//errorMSG(page, 0);
            errorMSG("Success!, now redirecting you to the drivers list.",0);
            window.location.replace("drivers.php");
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

function changePassword(dID){
	$.ajax({
			method: "POST",
			url: "driverHelper.php",
			data: { action:"driverNewpass", dID: dID }
		}).done(function( page ) {
			errorMSG(page, 0);
		});
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
	 if (text.length == 0) {
		return false;
	} else {
		return true;
	}
}
/*Validate Email*/
function emailValidate(email){
	var error="";
    var tfld = trim(email);                        // value of field with whitespace trimmed off
    var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
    var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
    
    if (email == "") {
		return false;
    } else if (!emailFilter.test(tfld)) {
		return false;
    } else if (email.match(illegalChars)) {
		return false;
    } else {
        return true;
    }
}
/*Validate Phone Number*/
function phoneValidate(phone){
	
	var stripped = phone.replace(/[\(\)\.\-\ ]/g, '');     
   if (phone == "") {
		return false;
    } else if (isNaN(parseInt(stripped))) {
		return false;
    } else if (!(stripped.length == 10)) {
		return false;
    } else{
		return true;
	}
	
}
/*Highlight required*/
function highlightRequired(idName){
	$("#"+idName).css({"border":"1px solid red"});
}

function trim(s)
{
  return s.replace(/^\s+|\s+$/, '');
} 

/*
##########################################
############# TABS FUNCTIONS #############
##########################################
*/


$( document ).ready(function() {
	if(document.getElementById('tabs')){
		init();
	}
	
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


    