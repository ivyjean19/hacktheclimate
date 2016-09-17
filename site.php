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
$sql = "SELECT lat, lon FROM senslopedb.site_column ";
$result = mysqli_query($conn, $sql);

$numSites = 0;
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
       array_push($sitesLAT, $row["lat"]);
       array_push($sitesLON, $row["lon"]);
    
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
    <title>Simple markers</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 80%;
        width: 70%;
        display: block;
        margin: auto;

      }
    </style>
  </head>
  <body>
   <div class="brand">Hack the Climate</div>
              <div id="map"></div>
                       
    <script>
    var url = "http://localhost/temp/getSenslopeData.php?sitenames&db=senslopedb"
    $.getJSON(URL, function(data, status) {
          console.log(data);
        });
      // $.ajax({url: "http://weather.asti.dost.gov.ph/home/index.php/api/data/190/from/2016-01-01/to/2016-04-04", success: function(result){
      //   alert(result);
      // }
                
    var sitesLAT = <?php echo json_encode($sitesLAT); ?>;
    var sitesLON = <?php echo json_encode($sitesLON); ?>;
   for (var i = 0; i < sitesLAT.length; i++) {}

       function initMap() {
        var myLatLng = {lat: 14.5755335, lng: 121.0494221};
        

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });
        for (var i = 0; i < sitesLAT.length; i++) {
          var myLatLng2 =new google.maps.LatLng(sitesLAT[i],sitesLON[i]);
        var marker = new google.maps.Marker({
          position: myLatLng2,
          map: map,
          icon: {
          url: "img/bullet.gif",
        
          }
        });
    }
      }

      // google.maps.event.addDomListener(window, 'load', initialize_map);
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?client385290333225-1olmpades21is0bupii1fk76fgt3bf4k.apps.googleusercontent.com?key=AIzaSyDbS_ZZ4dsNkzNwFMEDCfDpUkMkOHa5CH8&callback=initMap">
    </script>
 

    </script>
  </body>
</html>