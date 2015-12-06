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
        var cName;
        var location;
        
        //remove the old markers
        deleteMarkers();
        
        //Add markers for each client
        for(var idx in data){
            lat = parseFloat(data[idx].cLat);
            lng = parseFloat(data[idx].cLng);

            cName = data[idx].cLastName + ", " + data[idx].cFirstName;
            location = {lat: lat, lng: lng};
            
            if(data[idx].rSuccess == 1){
                addMarker(location, cName, 'green', data[idx]);
            }else{
                addMarker(location, cName, 'red', data[idx]);
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
        
        var lat = parseFloat(data.curLat);
        var lng = parseFloat(data.curLng);

        var dName = data.dLastName + ", " + data.dFirstName;
        
        if(!data.curLat || !data.curLng){
            var jsonData = {lat: 26.127516, lng: -80.202787};
            replaceMarker(jsonData, dName);
        }else{
            var jsonData = {lat: lat, lng: lng};
            replaceMarker(jsonData, dName);
        }

    });
    
});




var map;
var dMarker;
var MOWMarker;
var markers = [];
var infowindow;

    
function initMap() {
    var location = {lat: 26.127516, lng: -80.202787};

    map = new google.maps.Map(document.getElementById('overviewMap'), {
        zoom: 13,
        center: location
    });

    var contentString = "<p>Meals on Wheels Home Lcation.</p>";
    infowindow = new google.maps.InfoWindow({
        content: contentString,
        maxWidth: 300
    });
    
    var image = 'img/mow.png';
    MOWMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: 'Meals on Wheels',
        icon: image 
    });
    
    MOWMarker.addListener('click', function() {
        infowindow.open(map, MOWMarker);
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
    //map.setCenter(dMarker.getPosition());
    map.panTo(dMarker.getPosition());
}


// Adds a marker to the map and push to the array.
function addMarker(location, title, color, data) {

    var name = data.cLastName + ", " + data.cFirstName;
    var phone = data.cPhone.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, '($1) $2-$3');
    
    var popupHtml = "<h3>" + name + "</h3><br/><p><b>Address:</b><br/>" + data.cAddress1 + "</p><p><b>Phone:</b><br/>" + phone +"</p><p><b>Notes:</b><br/>" + data.cDeliveryNotes + "</p>"; 

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
    marker.id = data.cID;
    marker.popupHtml = popupHtml;
    
    marker.addListener('click', function() {
        infowindow.setContent(this.popupHtml);
        infowindow.open(map, marker);
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

//Opens the info window when a user clicks on a client.
$(document).on('click','.clientMapRow',function(){

    var $row = $(this);

    for (var i = 0; i < markers.length; i++) {
        if(markers[i].id == $row.data('cid')){
            infowindow.setContent(markers[i].popupHtml);
            infowindow.open(map, markers[i]);
        }
    }
});

function KeyPress(e) {
    var evtobj = window.event? event : e
    if (evtobj.keyCode == 52 && evtobj.ctrlKey && evtobj.altKey){
        
        setPHPOffset(4);
        
    }else if(evtobj.keyCode == 48 && evtobj.ctrlKey && evtobj.altKey){

        setPHPOffset(0);
        
    } 
}
document.onkeydown = KeyPress;

function setPHPOffset(offset){
    //Set php session variables for page reload.
    $.ajax({
        method: "POST",
        url: "indexHelper.php",
        data: { 
            action:"loadSpecificData",
            offset: offset
        }
    }).done(function(returnData){
        alert("Overview page will be loaded with data from " + offset + " days ago.");
        location.reload();
    });
}