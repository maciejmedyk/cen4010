<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>MOV Tracker Directions Page</title>
    <link rel="stylesheet" type="text/css" href="css/map.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
  </head>
  <body> 
    <div id="map"></div>
    <div id="bottom-panel">
      <br>
      <div id="waypoints-panel">
        <b>Waypoints:</b><br>
        <i>(Select multiple customers as stops)</i> <br>
        <select multiple id="waypoints">
          <option value="26.365837, -80.106009">Customer 1</option>
          <option value="26.396169,-80.130693">Customer 2</option>
          <option value="26.407784, -80.099649">Customer 3</option>
          <option value="26.440248, -80.101051">Customer 4</option>
        </select>
        <br>
        <input type="submit" id="submit">
      </div>
    </div>
    <div id="directions-panel"></div>
    <div id="text-panel"></div>
    
<?php require './includes/deviceDetection.php';?>

<script>
var initialLocation;
//var siberia = new google.maps.LatLng(60, 105);
//var newyork = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
var browserSupportFlag =  new Boolean();

//Called from the REST api callback at the bottom of the page.
function initMap() {
  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer;
  var directionsDisplayText = new google.maps.DirectionsRenderer;
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 10,
    center: {lat: 41.85, lng: -87.65}
  });
  directionsDisplay.setMap(map);
  directionsDisplayText.setMap(map);
  directionsDisplayText.setPanel(document.getElementById('text-panel'));


  document.getElementById('submit').addEventListener('click', function() {
    calculateAndDisplayRoute(directionsService, directionsDisplay, directionsDisplayText);
  });
  
  // Try W3C Geolocation (Preferred)
  if(navigator.geolocation) {
    browserSupportFlag = true;
    navigator.geolocation.getCurrentPosition(function(position) {
      initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
      map.setCenter(initialLocation);
    }, function() {
      handleNoGeolocation(browserSupportFlag);
    });
  }
  // Browser doesn't support Geolocation
  else {
    browserSupportFlag = false;
    handleNoGeolocation(browserSupportFlag);
  }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
}

function calculateAndDisplayRoute(directionsService, directionsDisplay, directionsDisplayText) {
  var waypts = [];
  var checkboxArray = document.getElementById('waypoints');
  for (var i = 0; i < checkboxArray.length; i++) {
    if (checkboxArray.options[i].selected) {
      waypts.push({
        location: checkboxArray[i].value,
        stopover: true
      });
    }
  }

  directionsService.route({
    origin: initialLocation, //document.getElementById('start').value,
    destination: initialLocation,//document.getElementById('end').value,
    waypoints: waypts,
    optimizeWaypoints: true,
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {
    if (status === google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
      directionsDisplayText.setDirections(response);
      var route = response.routes[0];
      var summaryPanel = document.getElementById('directions-panel');
      summaryPanel.innerHTML = '';
      

      // For each route, display summary information.
      for (var i = 0; i < route.legs.length; i++) 
      {
        var routeSegment = i + 1;
        summaryPanel.innerHTML += '<b>Stop ' + routeSegment + ':</b><br>';
        //summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
        summaryPanel.innerHTML += '<b>Address:</b> ' + route.legs[i].end_address + '<br>';
        if (routeSegment == 1){
          summaryPanel.innerHTML += 'Distance from current location: ' + route.legs[i].distance.text + '<br>';
        }else{
          summaryPanel.innerHTML += 'Distance from last stop: ' + route.legs[i].distance.text + '<br>';
        }
        
        //Show navigation button depending on platform
        if ("<?php echo isDevice('android');?>")
        {
          summaryPanel.innerHTML += '<center><a class="btn btn-info" role="button" href="google.navigation:q=' + route.legs[i].end_location + '";>Navigate with Native App</a></center><br>';
        }
        else if ("<?php echo isDevice('ios');?>") 
        {
          summaryPanel.innerHTML += '<center><a class="btn btn-info" role="button" href="http://maps.apple.com/?q=' + route.legs[i].end_location + '";>Navigate with Native App</a></center><br>';
        }
        summaryPanel.innerHTML += "<hr>";
      }
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUacGLhz_V_YNulU_YET1DwK4d2Y_g8M8&signed_in=true&callback=initMap"
        async defer></script>
  </body>
</html>