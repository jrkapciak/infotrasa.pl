<!DOCTYPE html>
<html>
<head>
    <title>Cyfrowy Atlas Samochodowy - Mapa</title>
    <meta charset="utf-8" />
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="styles/leaflet-routing-machine1.css" />
    <link rel="stylesheet" href="leaflet/leaflet.css"/>
    <link rel="stylesheet" href="styles/L.Control.Locate.scss"/>
    <link rel="stylesheet" href="styles/easy-button.css"/>
    <link rel="stylesheet" href="styles/Leaflet.Weather1.css"/>
    <link rel="stylesheet" href="styles/leaflet.extra-markers.min.css">
    <link rel="stylesheet" href="styles/leaflet.contextmenu.min.css">
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
    <script src="js/L.Polyline.SnakeAnim.js"></script>
    <script src="js/L.Control.Locate.js"></script>
    <script src="js/updater.js"></script>
    <script src="js/leaflet.contextmenu.min.js"></script>
    <script src="js/Leaflet.Weather1.js"></script>


  
  

   
    <?php
    $url = "http://www.gddkia.gov.pl/dane/zima_html/utrdane.xml";
    $img ="utrudnienia.xml";
	$file = file_put_contents($img, file_get_contents($url));
	?>
    
        
      
</head>
<body>



    <div id="output"></div>
    <div id="map" style="width: 99vw; height: 98vh; padding; margin:;"></div>
    <script>

      function showCoordinates (e) {
	    control.spliceWaypoints(0, 1, e.latlng);
	  }
      
      function centerMap (e) {
	      control.spliceWaypoints(control.getWaypoints().length - 1, 1, e.latlng);
      }

      function zoomIn (e) {
	      map.zoomIn();
      }

      function zoomOut (e) {
	      map.zoomOut();
      }
	  
    var map = L.map('map', {
	    center: [52.2858, 20.78],
	    zoom: 7,
	    contextmenu: true,
		contextmenuWidth: 150,
	    contextmenuItems: [{
		      text: 'Początek prodróży',
			  icon: 'image/start.png',
		      callback: showCoordinates
			}, {
		      text: 'Cel podróży',
			  icon: 'image/end.png',
		      callback: centerMap
			}, '-', {
		      text: 'Przybliż',
		      icon: 'image/zoom-in.png',
		      callback: zoomIn
			}, {
		      text: 'Oddal',
		      icon: 'image/zoom-out.png',
		      callback: zoomOut
	  }]
      });

	  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		  attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	  }).addTo(map);
	  

	  
	  map.locate({setView: true, maxZoom: 7});
	  
	  var start
	  function onLocationFound(e) {
		var radius = e.accuracy / 2;
		control.spliceWaypoints(0, 1, e.latlng)
		
		}
		map.on('locationfound', onLocationFound);

		
             
     // routin - place in separate js file
        var control = L.Routing.control({
			waypoints: [start],
			createMarker: function(i, wp) {
				if (i==0){
					return L.marker(wp.latLng, {icon: startMarker,}).addTo(map);}
				else if (i==1){
					return L.marker(wp.latLng, {icon: endMarker,}).addTo(map);
				}
			},
			
			routeWhileDragging: true,
			showAlternatives: true,
			
			lineOptions: {
                    styles: [{color: '#2A833A', opacity: 0.99, weight: 7}]
                },
             altLineOptions: {
                    styles: [
                        {color: '#058EC4', opacity: 0.99, weight: 6}]
                },
			geocoder: L.Control.Geocoder.nominatim()
			}).addTo(map);




		
        L.control.locate({
		drawMarker: true,
		}).addTo(map);
		
	L.control.weather({
    lang: "pl",
    units: "metric"
    }).addTo(map);
		
		
        L.easyPrint({
			title: 'Wydrukuj mapę',
		}).addTo(map);
        
       cities = L.layerGroup([]);
	   
	   
        // Creates a red marker with the coffee icon
        var orangeMarker = L.ExtraMarkers.icon({
                icon: 'ion-alert-circled',
                markerColor: 'orange',
                shape: 'square',
                prefix: 'ion'
                });
		var blueMarker = L.ExtraMarkers.icon({
                icon: 'ion-alert-circled',
                markerColor: 'blue',
                shape: 'square',
                prefix: 'ion'
                });
		var startMarker = L.ExtraMarkers.icon({
                icon: 'ion-android-car',
                markerColor: 'blue',
                shape: 'circle',
                prefix: 'ion'
                });
		var endMarker = L.ExtraMarkers.icon({
                icon: 'ion-android-car',
                markerColor: 'green',
                shape: 'circle',
                prefix: 'ion'
                });
	var toggle = L.easyButton({
		states: [{
		stateName: 'add-markers',
		icon: 'icon ion-alert-circled',
		title: 'Pokaż utrudnienia',
		onClick: function(control) {
  
               a = L.layerGroup([]);
                $(document).ready(function(){
                $.ajax({
                    type: "GET",
                    url: "utrudnienia.xml",
                    dataType: "xml",
                    success: parseXml
                    });
                });
                function parseXml(xml){ 
                    $(xml).find("utrudnienia utr").each(
                    function(){
                    var mapPoint = L.marker([$(this).find("geo_lat").text(),$(this).find("geo_long").text()], {icon: orangeMarker,})
         
                
                    var objazd =  $(this).find("objazd").text();
                    var objazdDlugosc =  $(this).find("objazd").text().length;
                
                    var ruch_wahadlowy = $(this).find("ruch_wahadlowy").text();
                    var rodzaj = $(this).find("poz").text();
						if (rodzaj === "U33"){rodzaj = "<b> Roboty drogowe </b>";}
					
						else if 
							(rodzaj === "J04"||rodzaj === "R10"||rodzaj === "U42"||rodzaj === "K03"||rodzaj === "I04")
							{rodzaj = "<b> Inne utrudnienie </b> ";}
						else if (rodzaj === "I02"){rodzaj = "Blokada drogi";}
						else if (rodzaj === "I05"){rodzaj = "Rajd";}
						else if (rodzaj === "I06"){rodzaj = "Pielgrzymka";}
						else if (rodzaj === "I07"){rodzaj = "Zawody sportowe";}
						else if (rodzaj === "I08"){rodzaj = "Demonstracja";}
						else if (rodzaj === "I09"){rodzaj = "Uroczystość";}
						else if (rodzaj === "I10"){rodzaj = "Przejazd ważnej osobistości";}
						else if (rodzaj === "I11"){rodzaj = "Przejazd pojazdu nienormatywnego";}
						else if (rodzaj === "I12"){rodzaj = "Zwiększone natężenie ruchu";}
						else if (rodzaj === "I12"){rodzaj = "Protest";}
						else if (rodzaj === "K01"){rodzaj = "Katastrofa budowlana";}
						else if (rodzaj === "K02"){rodzaj = "Katastrofa ekologiczna";}
						else if (rodzaj === "K04"){rodzaj = "Pożar";}
						else if (rodzaj === "S00"){rodzaj = "Brak informacji";}
						else if (rodzaj === "U27"){rodzaj = "Remont mostu";}
						else if (rodzaj === "R14"){rodzaj = "Awaria pojazdu";}
						else if (rodzaj === "J02"){rodzaj = "Przerwa w ruchu";}
						else {rodzaj = "Wypadek drogowy";}

			
                    if (objazdDlugosc > 1)
                        if (ruch_wahadlowy == 'true')
                        {mapPoint.bindPopup("<b>"+rodzaj+"</b>" +"</br> Ruch wahadłowy </br> <b> opis: </b> <i> " + objazd + "</i>");}
                        else 
                        {mapPoint.bindPopup("<b>"+rodzaj+"</b>"+"</br><b> opis: </b><i>" + objazd + "</i>");}
                    
                    else
                    {
                        mapPoint.bindPopup( "<b>"+rodzaj+"</b>"+ "</br> <b> opis: </b><i>brak</i>  ");
                    }
                    a.addLayer(mapPoint)
               
               
                    });}
                    ;
					
					
					
					
	// add the weather control

// add the weather control

// add the weather control



                
        
     
      map.addLayer(a);
      control.state('remove-markers');
    }}, {
    icon: 'fa-undo',
    stateName: 'remove-markers',
    onClick: function(control) {
    
      map.removeLayer(a);
      control.state('add-markers');
    },
    title: 'ukryj utrdnienia'
  }]
});
toggle.addTo(map);
</script>

<script>
$(function(){
	setInterval(onLocationFound, 1000);
				});
</script>
    
    

</body>
</html>