<?php
include("dsession.php");
#$id=$_SESSION['customer_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-O-W Delivery</title>

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">


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
            <a href="dhome.php"<button type="link" id="logoutButton" class="btn btn-default"> Back </button></a>
        </div>
    </div><br>
    <div id="dWireFrame">
        <b align="center" id="welcome">Driver : <?php echo $login_name; ?></b><br>
        <p><?php date_default_timezone_set("America/New_York"); $date = date("Y-m-d"); $datetime = date("Y-m-d H:i"); print $datetime; ?></p>
        <div id="dInsideFrame">
            <a href="tel:911"<button type="link" id="emeButton" class="btn btn-default"> Call 911 </button></a>
            <form action="demergency.php" method="post">
            <button type="submit" id="emeButton" class="btn btn-default"> Location </button><br>
            <textarea rows="2" id="currentLocation" name="coordinates" value="" disabled"></textarea>
            </form>

            <div id="map"></div>

            <script>
                var loc;
                function initMap() {
                    var map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: -34.397, lng: 150.644},
                        zoom: 6
                    });
                    var infoWindow = new google.maps.InfoWindow({map: map});

                    // Try HTML5 geolocation.
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            var loc = pos['lat'] + ', ' + pos['lng'];
                            infoWindow.setPosition(pos);
                            infoWindow.setContent(loc);
                            document.getElementById('currentLocation').value = loc;

                            map.setCenter(pos);
                        }, function() {
                            handleLocationError(true, infoWindow, map.getCenter());
                        });
                    } else {
                        // Browser doesn't support Geolocation
                        handleLocationError(false, infoWindow, map.getCenter());
                    }
                }

                function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
                }
            </script>



            <?php
            if (isset($_POST['coordinates']))
            {
                include "connection.php";
                $coordinates = $_POST['coordinates'];
                $query = "INSERT INTO emergency (dID, eDate, eCoordinates) VALUES ('$login_id', '$datetime', '$coordinates')";
                $data = mysql_query ($query)or die(mysql_error());
                if($data)
                {
                    Print "<div id=\"emergencyConfirmation\"><p>Coordinates Sent to Administrator</p></div>";
                }
            }
            ?>

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUacGLhz_V_YNulU_YET1DwK4d2Y_g8M8&signed_in=true&callback=initMap"
                    async defer></script>

            <?php mysql_close($connection); ?>
        </div>
    </div>
</div>
</div>
</body>
</html>