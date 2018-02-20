var geocoder;
var map;
var marker;
var drawingManager;
var selectedShape;
var colors = ['#1E90FF', '#ff143b', '#32CD32', '#FF8C00', '#120082', '#8285a5', '#429998', '#97fffe'];
var selectedColor;
var colorButtons = {};

function clearSelection() {
    if (selectedShape) {
        selectedShape.setEditable(false);
        selectedShape = null;
    }
}

function cordenadas() {
    if(selectedShape){
        var color_interno = selectedShape.get('fillColor');
        document.getElementById("searchresults").innerHTML = '';
        selectedShape.getPath().forEach(function (vertex, inex) {
            document.getElementById("searchresults").innerHTML += '('+vertex.lat()+','+vertex.lng()+')' + ((inex<selectedShape.getPath().getLength()-1)?'|':'');
        });
        document.getElementById("searchresults").innerHTML += '@('+color_interno+')' ;
    }
}

function setSelection(shape) {
    clearSelection();
    selectedShape = shape;
    shape.setEditable(true);
    selectColor(shape.get('fillColor') || shape.get('strokeColor'));
}

function deleteSelectedShape() {
    if (selectedShape) {
        selectedShape.setMap(null);
    }
}

function selectColor(color) {
    selectedColor = color;
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
    }

    var polygonOptions = drawingManager.get('polygonOptions');
    polygonOptions.fillColor = color;
    drawingManager.set('polygonOptions', polygonOptions);
}
function setSelectedShapeColor(color) {
    if (selectedShape) {
        if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
            selectedShape.set('strokeColor', color);
        } else {
            selectedShape.set('fillColor', color);
        }
    }
}

function makeColorButton(color) {
    var button = document.createElement('span');
    button.className = 'color-button';
    button.style.backgroundColor = color;
    google.maps.event.addDomListener(button, 'click', function() {
        selectColor(color);
        setSelectedShapeColor(color);
    });

    return button;
}

function buildColorPalette() {
    var colorPalette = document.getElementById('color-palette');
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        var colorButton = makeColorButton(currColor);
        colorPalette.appendChild(colorButton);
        colorButtons[currColor] = colorButton;
    }
    selectColor(colors[0]);
}

$(document).ready(function(){
    $("#geocode").submit(codeAddress);
});

$(window).load(function() {
    initializeMap();
});

function initializeMap() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-16.49901,-68.146248);
    var myOptions = {
        zoom: 12,
        center: latlng,
        mapTypeControl: false,
        streetViewControl: false,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
    drawing_controls();

}

function codeAddress() {
    var address = document.getElementById("address").value;
    geocoder.geocode( {
        'address': address
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            map.setZoom(12);
            marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            var infowindow = new google.maps.InfoWindow({
                content: results[0].formatted_address
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });
            
            performIntersection(results[0].geometry.location.lat(), results[0].geometry.location.lng())
        } else {
            alert("La posicion del punto no fue encontrada por las siguientes razones: "+ status)
        }
    });
}

function drawing_controls(){
    drawingManager = new google.maps.drawing.DrawingManager({
        //        drawingMode: google.maps.drawing.OverlayType.MARKER,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_RIGHT,
            drawingModes: [
            google.maps.drawing.OverlayType.POLYGON
            ]
        },
        polygonOptions: {
            fillColor: '#ffff00',
            fillOpacity : 0.4,
            strokeWeight : 1,
            clickable : true,
            editable :true,
            zIndex :1
        }
    });
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        if (e.type != google.maps.drawing.OverlayType.MARKER) {

            drawingManager.setDrawingMode(null);

            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function() {
                setSelection(newShape);
            });
            setSelection(newShape);
        }
    });
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
    google.maps.event.addDomListener(document.getElementById('obtener-coordenadas'), 'click', cordenadas);
    buildColorPalette();
    drawingManager.setMap(map);
}