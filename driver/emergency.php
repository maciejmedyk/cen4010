<?php
include_once("session.php"); 
include_once("../header.php"); 
?>
<body>
<div id="dBackDiv">
    <div id="dHead">
        <div id="dHeadLeft">
            <a href="#"<button type="link" id="emergencyButton" class="btn btn-default"> Emergency </button></a>
        </div>
        <div id="dHeadRight">
            <a href="index.php"<button type="link" id="logoutButton" class="btn btn-default"> Back </button></a>
        </div>
    </div><br>
    <div id="dWireFrame">
        <b align="center" id="welcome">Driver : <?php echo $login_name; ?></b><br>
        <p><?php date_default_timezone_set("America/New_York"); $date = date("Y-m-d"); $datetime = date("Y-m-d H:i"); print $date; ?></p>
        <div id="dInsideFrame">
            <a href="tel:911"<button type="link" id="emeButton" class="btn btn-default"> Call 911 </button></a>
            <form action="emergency.php" method="post">
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
				$coordinates = $_POST['coordinates'];
				$newStudent = "INSERT INTO emergency (dID, eDate, eCoordinates) VALUES ('$driverID', '$datetime', '$coordinates')";
				$db->query($newStudent);
                
                if($db->affected_rows)
                {
                    Print "<div id=\"emergencyConfirmation\"><p>Coordinates Sent to Administrator</p></div>";
                }
            }
            ?>

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUacGLhz_V_YNulU_YET1DwK4d2Y_g8M8&signed_in=true&callback=initMap"
                    async defer></script>
        </div>
    </div>
</div>
</div>
</body>
</html>