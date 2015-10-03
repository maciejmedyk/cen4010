<?php
include("dsession.php");
$id=$_SESSION['customer_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-O-W Delivery</title>

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/main.css" rel="stylesheet" type="text/css">
    <link href="css/responsive.css" rel="stylesheet" type="text/css">
    <link href="css/map.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="dBackDiv">
    <div id="dHead">
        <div id="dHeadLeft">
            <a href="demergency.php"<button type="link" id="emergencyButton" class="btn btn-default"> Emergency </button></a>
        </div>
        <div id="dHeadRight">
            <a href="dsheets.php"<button type="link" id="logoutButton" class="btn btn-default"> Back </button></a>
        </div>
    </div><br>
    <div id="dWireFrame">
        <b align="center" id="welcome">Driver : <?php echo $login_name; ?></b><br>
        <p><?php date_default_timezone_set("America/New_York"); $date = date("Y-m-d"); print $date; ?></p>
        <div id="dInsideFrame">
            <?php
                include('connection.php');
                $data = mysql_query("SELECT * FROM `clients` WHERE `clients`.`cID` = $id") or die(mysql_error());
                $address = array();
                Print "<table border cellpadding = 3 class=\"driverDetailsSheets\">";
                Print "<th width=\"100%\">Address</th>";
                while ($info = mysql_fetch_array($data))
                {
                    Print "<tr><td id=\"dDSAddress1\">" . $info['cAddress1'] . ' ' . $info['cAddress2'] . "</td></tr>";
                    Print "<tr><td id=\"dDSAddress2\">" . $info['cCity'] . ', ' . $info['cState'] . ' ' . $info['cZip'] . "</td></tr>";
                    $address = $info['cAddress1'] . ' ' . $info['cCity'] . ' ' . $info['cState'] . ' ' . $info['cZip'];
                }
                Print "</table>";
            ?>
            <?php
            require './map/geocode.php';
            #echo $address; print "<br>";
            $geo = (getGeoLocation($address));
            #Print "<td id=\"dDSAction\"><form action=\"#\" method=\"post\"><input id=\"waypoint\" value=\"" .  $geo['lat'] . ', ' . $geo['lng'] . "\"></form></td></tr>";
            ?>

            <div id="map"></div>
            <div id="directions-panel"></div>
            <div id="text-panel"></div>
            <div id="bottom-panel">
                <div id="waypoints-panel">
                    <select class="hidden" id="finaldestination">
                        <option value="<?php echo $geo['lat'] . ',' . $geo['lng']; ?>">Customer 1</option>
                    </select>
                    <!--<input class="btn btn-default button" type="submit" id="submit" value="Calculate Route">-->
                </div>
            </div>

            <?php require './map/device.php'; ?>

            <script type="text/javascript">

                //
                //Variables for obtaining geolocation
                //
                var geoID;
                var browserSupportFlag =  new Boolean();
                var currentLocation = {
                    lat: 0,
                    lng: 0
                };
                
                var geoOptions = {
                    enableHighAccuracy: false,
                    timeout: 5000,
                    maximumAge: 0
                };
                
                //
                //This function will get the geolocation coordinates of the users current position.
                //Returns the coordinates.
                //
                function getLocation(position) {
                    browserSupportFlag = true;
                    if (position === undefined){
                        return;
                    }
                    currentLocation.lat = position.coords.latitude;
                    currentLocation.lng = position.coords.longitude;
                    
                    initMap(); //Should run this only the first time if autorefresh should be left on. 
                    //Turn off the auto refresh for now. 
                    navigator.geolocation.clearWatch(geoID);                 
                }
                
                //
                //This will be called if there is an error getting the location.
                //
                function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                    browserSupportFlag = false;
                }
                
                //
                //This will initialize the geolocation functions (successful, not successful, options of locator)
                //
                function startGeolocation(){
                    geoID = navigator.geolocation.watchPosition(getLocation, handleLocationError, geoOptions);
                }

                var map;

                //
                //Initializes the google maps API
                //
                function initMap() {
                    var directionsService = new google.maps.DirectionsService;
                    var directionsDisplay = new google.maps.DirectionsRenderer;
                    var directionsDisplayText = new google.maps.DirectionsRenderer;
                        map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 11, center: currentLocation, disableDefaultUI: true});

                    directionsDisplay.setMap(map);
                    directionsDisplayText.setPanel(document.getElementById('text-panel'));
                    calculateAndDisplayRoute(directionsService, directionsDisplay, directionsDisplayText);
                }

                //
                //This will calculate the directions to all locations and display them on the screen.
                //
                function calculateAndDisplayRoute(directionsService, directionsDisplay, directionsDisplayText) {
                    var waypts = [];
                    console.log(currentLocation);

                    directionsService.route({
                        origin: currentLocation, //document.getElementById('start').value,
                        destination: document.getElementById('finaldestination').value,
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
                                //summaryPanel.innerHTML += '<u>Destination</u><br>';
                                //summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
                                summaryPanel.innerHTML += '<b>' + route.legs[i].end_address + '<b><br>';
                                if (routeSegment == 1){
                                    summaryPanel.innerHTML += '<i>Distance from current location: ' + route.legs[i].distance.text + '</i><br>';
                                }else{
                                    summaryPanel.innerHTML += '<i>Distance from last stop: ' + route.legs[i].distance.text + '</i><br>';
                                }

                                //Show navigation button depending on platform
                                if ("<?php echo isDevice('android');?>")
                                {
                                    summaryPanel.innerHTML += '<center><a class="btn btn-info bbb" role="button" href="google.navigation:q=' + route.legs[i].end_location + '";>Navigate with Google Maps</a></center><br>';
                                }
                                else if ("<?php echo isDevice('ios');?>")
                                {
                                    summaryPanel.innerHTML += '<center><a class="btn btn-info bbb" role="button" href="http://maps.apple.com/?q=' + route.legs[i].end_location + '";>Navigate with Apple Maps</a></center><br>';
                                }
                            }
                        } else {
                            window.alert('Directions request failed due to ' + status);
                        }
                    });
                }
            </script>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUacGLhz_V_YNulU_YET1DwK4d2Y_g8M8&signed_in=true&callback=startGeolocation"
                    async defer></script>
            <?php mysql_close($connection); ?>
        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>