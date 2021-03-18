// ****************************initialze map**********************************
var defaultLocation = [35.696733, 51.4899029];
var defaultZoom = 15;
var map = L.map('map').setView(defaultLocation, defaultZoom);
L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'map Project For You',
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1
}).addTo(map);

// ****************************main part of map********************************** //
var circle;
var markers = [];

function getDistance(from, to) {
    return (from.distanceTo(to)).toFixed(0);
}
// to display modal based on user login situation
function modalDisplayer(ev) {
    // add marker to map
    L.marker(ev.latlng).addTo(map);
    // show modal
    
    if (session != 0) {
        $("#addLocationModal").fadeIn(500);
    } else {
        // show login modal
        $("#loginModal").fadeIn(500);
    }
    // print lat and lng 
    $("#lat-display").val(ev.latlng.lat);
    $("#lng-display").val(ev.latlng.lng);
    // reset values of modal input  
    $("#l-title").val('');
    $("#l-type").val(0);
}
// marker location find 
function findMarkerInfoFromDatabaseAndDisplayModal(latlng) {
    $.ajax({
        url: site_url + '/process/findMarkerInfo.php',
        method: 'POST',
        data: {'lat' : latlng.lat, 'lng' : latlng.lng},
        success: function (response) {
            // turn location coming back from ajax result to javascript array
            var loc_array = JSON.parse(response);
            $("#markerInfoModal").fadeIn(500);
            // set values of the modal with location information
            $("#markerInfoModal #m-lat").val(loc_array['lat']);
            $("#markerInfoModal #m-lng").val(loc_array['lng']);
            $("#markerInfoModal #m-title").val(loc_array['title']);
            $("#markerInfoModal #m-phone").val(loc_array['phone']);
            $("#markerInfoModal #m-description").html(loc_array['description']);
            $("#markerInfoModal #m-type").val(locationTypes[loc_array['type']]);
        }
    });  
}
// make circles around 1000 meters of clicked point
function drawCircles(ev, radius = 1000) {
    // var radius = prompt("فاصله مورد نظر را در مقیاس کیلومتر وارد کنید"); // meters
    var radius = radius; // meters
    var locationsInRadius = [];
    var distance;
    var loc;
    // get ciricle center
    var center = ev.latlng;

    // remove last circle
    if (window.circle != undefined) {
    map.removeLayer(window.circle);
    };
    // remove last markers
    if (window.markers.length != 0) {
    for (var i1 = 0; i1 < window.markers.length; i1++) {
        theMarker = window.markers[i1];
        // remove marker
        map.removeLayer(theMarker);
    } 
    } // end if
    // draw a circle with defined radius
    window.circle = L.circle(center, {
    color: "007bec",
    fillColor: "#007bec",
    fillOpacity: 0.2,
    radius: radius
    }).addTo(map);  
    // check locations in the radius
    for (var i = 0; i < locations.length; i++) {
        //  get the location :: done
        loc = [locations[i]['lat'], locations[i]['lng']] 
        loc_title = locations[i]['title'];
        loc_phone = locations[i]['phone'];
        loc_description = locations[i]['description'];
        if (loc_title.length < 1) {
            loc_title = "نامشخص";
        }
        // calculate the distance between the center of the circle and the location :: done
        distance = ev.latlng.distanceTo(loc);
        //  add marker for all locations in the radius :: done
        if (distance < radius) {
            marker = L.marker(loc).addTo(map).bindPopup(loc_title).openPopup();
            
            // push marker to the markers array
            window.markers.push(marker);
            
            marker.on('click', function (e) {
                // console.log(e.latlng.lat);
                findMarkerInfoFromDatabaseAndDisplayModal(e.latlng);
            })
        }
    }

}

$(document).ready(function () {

    // difrentiate between double click and click
    var clickCount = 0;
    map.on('click', function(ev) {
        clickCount++;
        if (clickCount === 1) {
            singleClickTimer = setTimeout(function() {
                clickCount = 0;
                // draw circles here
                drawCircles(ev);
            }, 400);
        } else if (clickCount === 2) {
            clearTimeout(singleClickTimer);
            clickCount = 0;
            // display modal here
            modalDisplayer(ev);
        }
    }, false);
      
    /** Modals Part **/

    // fadeout locatons modal
    $("#addLocationModal .close").click(function () {
        $("#addLocationModal").fadeOut(500);
        // clear last ajax respond
        $("#addLocationModal .ajax-result").html('');
    });
    // fadeOut login modal
    $("#loginModal .close").click(function () {
        $("#loginModal").fadeOut(500);
    });
    // ajax request to save location
    $("form#addLocationForm").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var resultTag = $(".ajax-result");
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function (response) {
                resultTag.html(response);
            }
        }); 
    // end ajax request to save location
    }); 

    $("#searchResultModal .close").click(function () {
        $('#searchResultModal').fadeOut(500);
    });
    
    $("#markerInfoModal .close").click(function () {
        $('#markerInfoModal').fadeOut(500);
    });

});