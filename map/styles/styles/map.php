<!DOCTYPE html>
<html>
<head>
    <title>Cyfrowy Atlas Samochodowy - Mapa</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="styles/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="leaflet/leaflet.css"/>
    <link rel="stylesheet" href="styles/L.Control.Locate.scss"/>
    <link rel="stylesheet" href="styles/easy-button.css"/>
    <link rel="stylesheet" href="styles/leaflet.extra-markers.min.css">

    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/2adea7ed94.css">
 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"> </script>
    <script src="leaflet/leaflet.js"> </script>
    <script src="js/leaflet-routing-machine.js"></script>
    <script src="js/leaflet.easyPrint.js" ></script> 
    <script src="js/Control.Geocoder.js"></script>
    <script src="js/config.js"></script>
    <script src="js/easy-button.js"></script>
    <script src="js/leaflet.extra-markers.min.js"></script>
    <script src="js/L.Control.Locate.js"></script>

  

   
    <?php
    $url = "https://www.gddkia.gov.pl/dane/zima_html/utrdane.xml";
    $img ="utrudnienia.xml";
$file = file_put_contents($img, file_get_contents($url));
?>
    
</head>
<body>



   <div id="output"></div>
    <div id="map" style="width: 99vw; height: 98vh; padding:0; margin:0;"></div>
<script>
        var map = L.map('map').setView([52.2858, 20.78], 7);
            mapLink = 
            '<a href="https://openstreetmap.org">OpenStreetMap</a>';
            L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; ' + mapLink,
            maxZoom: 18,
            }).addTo(map);
            

            
            
        function createButton(label, container) {
            var btn = L.DomUtil.create('button', '', container);
            btn.setAttribute('type', 'button');
            btn.innerHTML = label;
            return btn;
            }

            map.on('click', function(e) {
        var container = L.DomUtil.create('div'),
            startBtn = createButton('Start from this location', container),
            destBtn = createButton('Go to this location', container);

            L.popup()
                .setContent(container)
                .setLatLng(e.latlng)
                .openOn(map);
});

     L.DomEvent.on(startBtn, 'click', function() {
        control.spliceWaypoints(0, 1, e.latlng);
        map1.closePopup();
    });
    
      L.DomEvent.on(destBtn, 'click', function() {
        control.spliceWaypoints(control.getWaypoints().length - 1, 1, e.latlng);
        map1.closePopup();
    });
            
     // routin - place in separate js file
        var control = L.Routing.control(L.extend(window.lrmConfig, {
            waypoints: [],
            geocoder: L.Control.Geocoder.nominatim(),
            routeWhileDragging: false,
            reverseWaypoints: true,
            showAlternatives: true,
            altLineOptions: {styles: [
                {color: 'black', opacity: 0.15, weight: 9},
                {color: 'white', opacity: 0.8, weight: 6},
                {color: 'blue', opacity: 0.5, weight: 2}]}
        })).addTo(map);

        L.Routing.errorControl(control).addTo(map);
        L.control.locate().addTo(map);
        L.easyPrint({
			title: 'Wydrukuj mapę',
		}).addTo(map);
        
        var utr = L.easyButton(
        'ion-android-warning', 
        function(){
                $(document).ready(function(){
                $.ajax({
                    type: "GET",
                    url: "utrudnienia.xml",
                    dataType: "xml",
                    success: parseXml
                    });
                });
                function parseXml(xml){ 
                    $(xml).find("utrudnienia utr").each(function(){
                // $("#output").append("Name: " + $(this).find("typ").text() + "<br />");
                // $("#output").append("opis: " + $(this).find("objazd").text() + "<br />");
                // $("#output").append("Województwo: " + $(this).find("woj").text() + "<br />");
                  var blueMarker = L.ExtraMarkers.icon({
                    icon: 'ion-heart',
                    markerColor: 'blue',
                    shape: 'square',
                    prefix: 'ion'
                    });
                
                var mapPoint = L.marker([$(this).find("geo_lat").text(),$(this).find("geo_long").text()], {icon: redMarker,})
                //var mapPoint=L.marker([$(this).find("geo_lat").text(),$(this).find("geo_long").text()]);
                
                var objazd =  $(this).find("objazd").text();
                var objazdDlugosc =  $(this).find("objazd").text().length;
                
                var ruch_wahadlowy = $(this).find("ruch_wahadlowy").text();
                var rodzaj = $(this).find("poz").text();
                mapPoint.addTo(map);
                if (objazdDlugosc > 1)
                    if (ruch_wahadlowy == 'true')
                    {mapPoint.bindPopup(rodzaj +"<b> Ruch wahadłowy </br> opis: </b> " + objazd);}
                    else 
                    {mapPoint.bindPopup(rodzaj +"<b> opis: </b> " + objazd);}
                    
                else
                {
                    mapPoint.bindPopup( rodzaj + "<b> opis: </b> brak  ");
                }
                
                });}
                })
                
                
        utr.addTo(map);
        
        // Creates a red marker with the coffee icon
        var redMarker = L.ExtraMarkers.icon({
                icon: 'ion-alert-circled',
                markerColor: 'orange',
                shape: 'square',
                prefix: 'ion'
                });

        L.marker([51.941196,4.512291], {icon: redMarker,}).addTo(map);
        
  

        

</script>

    

</body>
</html>