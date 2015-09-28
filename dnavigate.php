<?php
include("dsession.php");
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
            <a href="#"<button type="link" id="emergencyButton" class="btn btn-default"> Emergency </button></a>
        </div>
        <div id="dHeadRight">
            <?php
            if(empty($_POST["cID"]))
            {
            }
            else
            {
            $id = trim($_POST['cID']);
            }
            Print "<td id=\"dDSAction\"><form action=\"dsheets.php\" method=\"post\"><input class=\"hidden\" name=\"cID\" value=\"" .  $id . "\"><input type=\"submit\" id=\"logoutButton\" class=\"btn btn-default\" value=\"Back\"></form></td></tr>";
            ?>
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
                Print "</table><br>";
            ?>
            <?php
            require './map/geocode.php';
            #echo $address; print "<br>";
            $geo = (getGeoLocation($address));
            #Print "<td id=\"dDSAction\"><form action=\"#\" method=\"post\"><input id=\"waypoint\" value=\"" .  $geo['lat'] . ', ' . $geo['lng'] . "\"></form></td></tr>";
            ?>

            <div id="mapper"></div>
            <div id="bottom-panel">
                <div id="waypoints-panel">
                    <select class="hidden" id="waypoints">
                        <option value="<?php echo $geo['lat'] . ', ' . $geo['lng']; ?>">Customer 1</option>
                    </select>
                    <input class="btn btn-default button" type="submit" id="submit" value="Navigate">
                </div>
            </div>
            <div id="directions-panel"></div>
            <div id="text-panel"></div>

            <?php require './map/device.php'; ?>


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
                    var map = new google.maps.Map(document.getElementById('mapper'), {
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
        </div>
    </div>
</div>
</body>
</html>