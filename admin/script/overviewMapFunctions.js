//On click of driver info panel.  Update map.
$(document).on('click','.driverPanel',function(){
    $(".driverPanel").each(function(){
       $(this).removeClass("selectedPanel"); 
    });
    $(this).addClass("selectedPanel");
    var dID = $(this).data('did');
        
    //Get Clients table and update div
    $.ajax({
        method: "POST",
        url: "indexHelper.php",
        data: { action:"getClientInfo",dID: dID }
    }).done(function(returnData){
        $("#clientTable").html(returnData);
    });
    
    //Get Clients location data and update map
    $.ajax({
        method: "POST",
        url: "indexHelper.php",
        data: { action:"getMapInfo",dID: dID }
    }).done(function(returnData){
        var data = JSON.parse(returnData);

        var lat;
        var lng;
        var dName;
        var location;
        
        //remove the old markers
        deleteMarkers();
        
        //Add markers for each client
        for(var idx in data){
            lat = parseFloat(data[idx].cLat);
            lng = parseFloat(data[idx].cLng);
            dName = data[idx].cLastName + ", " + data[idx].cFirstName;
            location = {lat: lat, lng: lng};
            
            if(data[idx].rSuccess == 1){
                addMarker(location, dName, 'green');
            }else{
                addMarker(location, dName, 'red');
            }
        }
        showMarkers();
    });
    
    //Get driver info and update map.
    $.ajax({
        method: "POST",
        url: "indexHelper.php",
        data: { action:"getRouteInfo",dID: dID }
    }).done(function(returnData){
        
        var data = JSON.parse(returnData);
        var lat = parseFloat(data.lat);
        var lng = parseFloat(data.lng);
        var dName = data.dLastName + ", " + data.dFirstName;
        
        var jsonData = {lat: lat, lng: lng};
        
        replaceMarker(jsonData, dName);
    });
    
});


var map;
var dMarker;
var MOWMarker;
var markers = [];
    
function initMap() {
  var location = {lat: 26.127516, lng: -80.202787};

  map = new google.maps.Map(document.getElementById('overviewMap'), {
    zoom: 13,
    center: location
  });

    var image = 'img/mow.png';
  MOWMarker = new google.maps.Marker({
    position: location,
    map: map,
    title: 'Meals on Wheels',
    icon: image 
  });
}
    
function replaceMarker(location, title){
        
    if (dMarker != null){
        dMarker.setMap(null);
    }
    var image = 'img/car.png';
    dMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: title,
        icon: image
    });
    dMarker.setZIndex(google.maps.Marker.MAX_ZINDEX);
    map.setCenter(dMarker.getPosition());
}


// Adds a marker to the map and push to the array.
function addMarker(location, title, color) {
  
    var image;
    if (color == "red"){
        image = 'img/house-red.png';
    }else{
        image = 'img/house-green.png';
    }

    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: title,
        icon: image
    });
    markers.push(marker);
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}
