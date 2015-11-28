//On click of driver info panel.  Update map.
$(document).on('click','.driverPanel',function(){
    var dID = $(this).data('did');
    $.ajax({
        method: "POST",
        url: "indexHelper.php",
        data: { action:"getRouteInfo",dID: dID }
    }).done(function(returnData){
        
        var data = JSON.parse(returnData);
        var lat = parseFloat(data.lat);
        var lng = parseFloat(data.lng);
        
        var jsonData = {lat: lat, lng: lng};
        console.log(jsonData);
        replaceMarker(jsonData);

    });
    
    $.ajax({
        method: "POST",
        url: "indexHelper.php",
        data: { action:"getClientInfo",dID: dID }
    }).done(function(returnData){
        
        $("#clientTable").html(returnData);

    });
    
    
});

var map;
var marker;
    
function initMap() {
  var location = {lat: 26.127516, lng: -80.202787};

  map = new google.maps.Map(document.getElementById('overviewMap'), {
    zoom: 13,
    center: location
  });

  marker = new google.maps.Marker({
    position: location,
    map: map,
    title: 'Meals on Wheels'
  });
}
    
function replaceMarker(location, title){
        
    marker.setMap(null);
    marker = new google.maps.Marker({
        position: location,
        map: map,
        title: title
    });
    map.setCenter(marker.getPosition());
}

