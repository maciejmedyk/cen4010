//errorType = int 0 => fail, 1 => success
function errorMSG(errorString, errorType){
	console.log(errorString);
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