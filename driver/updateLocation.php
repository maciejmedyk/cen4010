<?php

    if(isset($_POST['dID']) && isset($_POST['lng']) && isset($_POST['lat'])){
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $dID = $_POST['dID'];
    }else{
        return;
    }
    echo $lat . "  " . $lng . "  " . $dID;
    //$query = "UPDATE drivers SET curLat=$lat, curLng=$lng WHERE dID=$dID;";
    //$sql = $db->query($query);

?>