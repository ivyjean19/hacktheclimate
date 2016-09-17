<?php
// Database login information
$servername = "localhost";
$username = "root";
$password = "senslope";
$dbname = "senslopedb";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sitesLAT =[];
$sitesLON =[];
$sites =[];
$ids =[];
$sql = "SELECT lat, lon , location,id FROM senslopedb.manila_raingauge";
$result = mysqli_query($conn, $sql);

$numSites = 0;
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
       array_push($sitesLAT, $row["lat"]);
       array_push($sitesLON, $row["lon"]);
       array_push($sites, $row["location"]);
       array_push($ids, $row["id"]);
    
    }
  }
$moisData =[];
$humidityData =[];
$tempData =[];
$airQdata =[];
$heatData =[];
$sql = "SELECT mois, hum , temp,heat,aq FROM senslopedb.aq_tabledata";
$result = mysqli_query($conn, $sql);

$numSites = 0;
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
       array_push($moisData, $row["mois"]);
       array_push($humidityData, $row["hum"]);
       array_push($tempData, $row["temp"]);
       array_push($airQdata, $row["aq"]);
        array_push($heatData, $row["heat"]);
        $mD[] = $row['mois'];
        $hD[] = $row['hum'];
        $tD[] = $row['temp'];
        $aD[] = $row['aq'];
        $htD[] = $row['heat'];
    
    }


} 

?>

<!DOCTYPE html>
<html>
  <head>
   
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>


    <meta charset="utf-8">
    <title>Hack the Climate</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/business-casual.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">
     <script src="js/jquery.js"></script>
       <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
    <title>Simple markers</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 80%;
        width: 90%;
        display: block;
        margin: auto;

      }
    </style>
  </head>
  <body>
   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="background:#000">
                <ul class="nav navbar-nav navbar-right">
                  <li>
                        <a href="#"  data-toggle="modal" data-target="#myModal2">About</a>
             
                    </li>
                    
                </ul>
                 <ul class="nav navbar-nav navbar-left">
                    <li>
                        <a href="#" class="dropbtn">Agrometeorological Stations</a>
                    </li>
                   
                </ul>

            </div>
            <br>
              <div id="map"></div>
              <hr>
              <div id="soilGraph"><img src="/img/soildata.png" alt="Soil Moisture" style="width:500px;height:50px;display: block; margin: auto;"></div>


      <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Historical data</h4>
        </div>
        <div class="modal-body">
        <div id="container" style="min-width: 850px; height: 350px; margin: 0 auto"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
   <div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Rain Graph</h4>
        </div>
        <div class="modal-body">
        <div id="container2" style="min-width: 850px; height: 350px; margin: 0 auto"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

</div>
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ABOUT</h4>
      </div>
      <div class="modal-body">
        <p>Portable agrometeorological (agromet) station network is a network of sensors that can detect Soil moisture, Barometric Temperature, relative Humidity, Heat Index and Air quality (by measuring PM 2.5).  The data from the stations is can be sent via GSM/GPRS or via Satellite communications. It can be deployed as a standalone device by just placing solar panels and Lead acid batteries on it. This device is can be placed on high vulnerable areas that are exposed to climate change such as forest, nature reserves and water reservoir. By monitoring such parameters it can prevent soil erosion, deforestation and even landslides</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

 <script>
  var sitesLAT = <?php echo json_encode($sitesLAT); ?>;
  var sitesLON = <?php echo json_encode($sitesLON); ?>;
   var sites = <?php echo json_encode($sites); ?>;
  var moisD = <?php echo json_encode($moisData); ?>;
  var humD = <?php echo json_encode($humidityData); ?>;
  var tempD = <?php echo json_encode($tempData); ?>;
  var airD = <?php echo json_encode($airQdata); ?>;
  var heatD = <?php echo json_encode($heatData); ?>;
  console.log('['+moisD.toString()+']');
 $(function () {
    $('#container').highcharts({
        title: {
            text: 'PLDT Innolab Test Data',
            x: -20 //center
        },
      
      
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Soil Moisture',
            data: [<?php echo join($mD, ',') ?>]
        }, {
            name: 'Humidity',
            data: [<?php echo join($hD, ',') ?>]
        }, {
            name: 'Temperature(celsius)',
            data:   [<?php echo join($tD, ',') ?>]
        }, {
            name: 'Air Quality (PM2.5 Quantity)',
            data: [<?php echo join($aD, ',') ?>]
         }, {
            name: 'Heat Index (celsius)',
            data: [<?php echo join($htD, ',') ?>]
        }]
        });

         $('#container2').highcharts({
        title: {
            text: 'Rain Test Data',
            x: -20 //center
        },
      
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Rain Value',
            data: [0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0]
        }]
        });
    });


        
  
       function initMap() {
        var myLatLng = {lat: 14.5755335, lng: 121.0494221};
        
          var infoWnd = new google.maps.InfoWindow({
        content :  "Pldt Innolab."
      });
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 10,
          center: myLatLng,
          styles:[{"featureType":"road","elementType":"geometry.fill","stylers":[{"lightness":-100}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"lightness":-100},{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":100}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"saturation":100},{"hue":"#006eff"},{"lightness":-19}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"saturation":-100},{"lightness":-16}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"hue":"#2bff00"},{"lightness":-39},{"saturation":8}]},{"featureType":"poi.attraction","elementType":"geometry.fill","stylers":[{"lightness":100},{"saturation":-100}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"saturation":-100},{"lightness":100}]},{"featureType":"poi.government","elementType":"geometry.fill","stylers":[{"lightness":100},{"saturation":-100}]},{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"lightness":100},{"saturation":-100}]},{"featureType":"poi.place_of_worship","elementType":"geometry.fill","stylers":[{"lightness":100},{"saturation":-100}]},{"featureType":"poi.school","elementType":"geometry.fill","stylers":[{"saturation":-100},{"lightness":100}]},{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"saturation":-100},{"lightness":100}]}]
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          clickable : true,
        });

        infoWnd.open(null, marker);

          google.maps.event.addListener(marker, "click", function() {
         $("#myModal").modal("show");
      });
        for (var i = 0; i < sitesLAT.length; i++) {
        
          var myLatLng2 =new google.maps.LatLng(sitesLAT[i],sitesLON[i]);
          var marker = new google.maps.Marker({
          position: myLatLng2,
          clickable : true,
          map: map,
          icon: {
          url: "img/bullet.gif",
          title: sites[i]
          },

        });

         marker.addListener('click', function() {
          $("#myModal3").modal("show");
        });


      }
      }
  

    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?client385290333225-1olmpades21is0bupii1fk76fgt3bf4k.apps.googleusercontent.com?key=AIzaSyDbS_ZZ4dsNkzNwFMEDCfDpUkMkOHa5CH8&callback=initMap">
    </script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
 

    </script>
  </body>
</html>