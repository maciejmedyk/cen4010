
var currentLat;
var currentLng;
var dID;

$(document).ready(function() {

    updateLocation();
    window.setInterval(updateLocation, 20000);
    
});


function updateLocation(){
    
    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function(position){
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            dID = $("#dID").data("did");
            currentLat = pos['lat'];
            currentLng = pos['lng'];
            console.log("Sending Ajax");
            $.ajax({
                method: "POST",
                url: "updatelocation.php",
                data: { 
                    dID: dID,
                    lat: currentLat,
                    lng: currentLng 
                }
            }).done(function(data){
                console.log(dID + " " + currentLat + " " + currentLng + " ");
                console.log(data);
            });
            console.log("after Ajax"); 
        });
    }
}