<?php
error_reporting(-1);
include ( __DIR__ . '/settings.php');

$link = "http://kruemel:1337/temperatureLogger/html/api.php";
$gets = array ("action" => "readValue", "key" => "FucK4sch001");
$gets["sens"] = filter_input(INPUT_GET, "sens");
if(!isset($gets["sens"])) {$gets["sens"] = "1";}
$gets["sens"] = dechex($gets["sens"]);
switch(strlen($gets["sens"])){
    case 1:
    $gets["sens"] = "0x00".$gets["sens"];
    break;
    case 2:
    $gets["sens"] = "0x0".$gets["sens"];
    break;
    case 3:
    $gets["sens"] = "0x".$gets["sens"];
    break;
}

$unit = NULL;
$json_sens = curl_get($link, $gets);
$sens = json_decode($json_sens);
//$i = count($sens->data);
?>
    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#sensors" aria-expanded="false" aria-controls="sensors">
                  <span data-feather="thermometer"></span>
                  Sensors
                  <span data-feather="chevron-right"></span>
                </a>
                <div class="col">
                  <div class="collapse multi-collapse" id="sensors">
                    <div class="card card-body">
                      <nav class="nav flex-column">               
                       <?php
                          for($ii = 1; $ii <= count($sensorNames)-1; $ii++){
                          echo "<a href='?graph&sens=".$ii."' class='nav-link'>Sensor " . $ii . " (<i>". $sensorNames[$ii] . "</i>)</a>";
                            }
                            ?>
                      </nav>
                    </div>
                  </div>
                </div>              
              </li>              
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Quickies</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#quicki" aria-expanded="false" aria-controls="quicki">
                 
                  SensorName #ID
                  <span data-feather="chevron-right"></span>
                </a>
                <div class="col">
                  <div class="collapse multi-collapse" id="quicki">
                    <span data-feather="thermometer"></span>: 88°<br>
                    <span data-feather="droplet"></span>: 99% 
                  </div>
                </div>              
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"><?php echo $sensorNames[hexdec($gets["sens"])]; ?></h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Import</button>
                <button class="btn btn-sm btn-outline-secondary">Export</button>
              </div>
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" id="timeDropper" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span data-feather="calendar"></span>
                This week
              </button>
              <div class="dropdown-menu" aria-labelledby="timeDropper">
                <a class="dropdown-item" href="#">Today</a>
                <a class="dropdown-item" href="#">This Month</a>
                <a class="dropdown-item" href="#">This year</a>
              </div>
            </div>
          </div>

          <canvas class="my-4" id="myChart" width="900" height="380"></canvas> 

          <?php if($sens->success){
            echo "<h2>".$i . " RAWwerte für " . $sensorNames[hexdec($gets["sens"])] . "  &nbsp;&nbsp;<small><code>ID:".$gets["sens"]."</code></small> </h2>";   ?>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
              <tr><th>Messung</th><th>SensorID</th><th>Temperatur</th><th>Feuchtigkeit</th><th>Datum</th><th>Kommentar</th><th>Accuspannung</th></tr>
              </thead>
              <tbody>
                <?php
                  foreach($sens->data as $row) {
                    echo "<tr>";
                    $unit = NULL;
                    foreach($row as $key => $val){        // decode HEX and Time from received json
                      switch($key){
                        case "temp":
                        case "humid":
                        case "accu":
                        $val = hexdec($val);
                          break;
                        case "time":
                        $val = date("d.m.Y - H:i:s",$val);
                        break;      
                      }
                      switch($key){                       // add units and comments
                        case "temp":
                        $val = $val/1000-OFFSET;
                        $unit = "°C";
                        break;
                        case "humid":
                        $val = $val/1000;
                        $unit = "%";
                        break;
                        case "accu":
                        if($val <= 3900) {$comment =  "<span class='badge badge-danger'>Danger</span>";}
                        $unit = "mV";
                        break;
                        case "comment":
                        $val = $comment;
                        $comment = NULL;
                        break;
                        case "time":
                        $unit = NULL;
                        break;
                      }
                      echo "<td>" . $val . $unit . "</td>";
                    }
                    echo "</tr>";
}
  }
               else {
                echo "<h3> Für&nbsp;" . $sensorNames[hexdec($gets["sens"])] . "  <small><code>ID:".$gets["sens"]."</code></small> sind keine Werte verfügbar! </h3>";
                echo "<div class='alert alert-danger'>Fehler: ";
                switch($sens->errorcode){
                  case "100":
                  echo "Sensor nicht gefunden! ";
                  break;
                }
                echo "</div><br>";
                echo "<div class='alert alert-info'>Fehlercode: <code>";
                print_r($json_sens);
                echo "</code></div>";

              }
                ?>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>

    
    <script>
      var ctx = document.getElementById("myChart");
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: [<?php // for($ii = 0; $ii <= 10; $ii++){ echo "\"".date("D, d.m.Y - H:i:s", $sens["data"][$ii]["time"])."\""; if($ii <= 10){echo ",";}} ?>],
          datasets: [{
            data: [<?php //for($ii = 0; $ii <= 10; $ii++){ echo hexdec($sens["data"][$ii]["temp"])/1000-OFFSET; if($ii <= 10){echo ",";}}    // devide by 1000 (float in arduino!!), substrate the OFFSET} ?>],
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            borderWidth: 4,
            pointBackgroundColor: '#007bff'
          }
          datasets: [{
            data: [<?php //for($ii = 0; $ii <= 10; $ii++){ echo hexdec($sens["data"][$ii]["humid"])/1000; if($ii <= 10){echo ",";} }     // devide by 1000 (float in arduino!!)} ?>],
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#212121',
            borderWidth: 4,
            pointBackgroundColor: '#000000'
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: false
              }
            }]
          },
          legend: {
            display: false,
          }
        }
      });
    </script>
  </body>
</html>

<?php
/*

for($ii = 0; $ii <= $i-1; $ii++){
                      echo hexdec($sens["data"][$ii]["accu"])."mV";
                  }

                  */ ?>
=======
<?php
$sensorNames = array (
  // just add an extra line for a new sensor
  0x000 => "none",      // Do not change this line!
  0x001 => "Arbeitszimmer",
  0x002 => "Wohnzimmer",
  0x003 => "Küche",
  0x004 => "Schlafzimmer",
  0x005 => "Balkon"
);

define('OFFSET', '273.15');  // define the temperature offset (0K)

$dataPoints = array();

function curl_get($url, array $get = NULL, array $options = array())
{
    $defaults = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
}
?>

    <div>
      <div class="w-row">
        <div class="w-col w-col-3">
          <div data-collapse="all" data-animation="over-left" data-duration="400" class="w-nav">
            <div class="w-container">
              <a href="#" class="brand w-nav-brand">
                <div class="text-block-2">Sensors</div>
              </a>
              <nav role="navigation" class="nav-menu-3 w-nav-menu">
                <?php
                 for($ii = 1; $ii <= count($sensorNames)-1; $ii++){
                //  echo "<form method=\"post\" class=\"w-nav-link\" action=\"#\"><input type=\"hidden\" name=\"sens\" value=\" ". $ii . " \"><button type=\"submit\">Sensor 0x00" . $ii . "1</button></form>";
                echo "<a href='?graph&sens=".$ii."' class='w-nav-link'>Sensor " . $ii . " (<i>". $sensorNames[$ii] . "</i>)</a>";
                }
                  ?>
                  </nav>
              <div class="w-nav-button">
                <div class="w-icon-nav-menu"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="w-col w-col-9">
          <div>
            <div class="w-row">
              <?php
                  $link = "http://kruemel:1337/temperatureLogger/html/api.php";
                  $gets = array ("action" => "readValue", "key" => "FucK4sch001");
                  $gets["sens"] = filter_input(INPUT_GET, "sens");
                  if(!isset($gets["sens"])) {$gets["sens"] = "1";}
                  $gets["sens"] = dechex($gets["sens"]);
                  switch(strlen($gets["sens"])){
                      case 1:
                      $gets["sens"] = "0x00".$gets["sens"];
                      break;
                      case 2:
                      $gets["sens"] = "0x0".$gets["sens"];
                      break;
                      case 3:
                      $gets["sens"] = "0x".$gets["sens"];
                      break;
                  }

//                    var_dump($gets["sens"]);
              $json_sens = curl_get($link, $gets);
              $sens = json_decode($json_sens, true);
              if($sens["success"] == "true"){
                  echo "<h3>".$i . " Werte für " . $sensorNames[hexdec($gets["sens"])] . "  &nbsp;&nbsp;<small><code>ID:".$gets["sens"]."</code></small> </h3>";
                  $i = count($sens["data"]);
                  echo "<table class='table table-dark'><thead><tr><td></td></tr></thead><tbody>";
                  echo "<b><tr><td>Messung</td><td>Temperatur</td><td>Feuchtigkeit</td><td>Datum</td><td>Kommentar</td><td>Accuspannung</td></tr></b>";
                  for($ii = 0; $ii <= $i-1; $ii++){
                    echo "<tr>";
                    echo "<td>";
                    echo $sens["data"][$ii]["id"];
                    echo "</td>";
                    echo "<td>";
                    echo hexdec($sens["data"][$ii]["temp"])/1000-OFFSET . "°C";    // devide by 1000 (float in arduino!!), substrate the OFFSET
                    echo "</td>";
                    echo "<td>";
                    echo hexdec($sens["data"][$ii]["humid"])/1000 . "%";      // devide by 1000 (float in arduino!!)
                    echo "</td>";
                    echo "<td>";
                    echo date("D, d.m.Y - H:i:s", $sens["data"][$ii]["time"]);
                    echo "</td>";
                    echo "<td>";
                    echo $sens["data"][$ii]["comment"];
                    echo "</td>";
                    echo "<td>";
                    if(hexdec($sens["data"][$ii]["accu"]) <= 3900){
                      echo "<div class=\"alert alert-danger\" role=\"alert\">".hexdec($sens["data"][$ii]["accu"])."mV</div>";
                    }
                    else {
                      echo hexdec($sens["data"][$ii]["accu"])."mV";
                    }
                    echo "</td>";
                    echo "</tr>";
                  }
                  echo "</table";
                } else {
                  echo "<h3> Für&nbsp;" . $sensorNames[hexdec($gets["sens"])] . "  <small><code>ID:".$gets["sens"]."</code></small> sind keine Werte verfügbar! </h3>";
                  echo "<div class='alert alert-danger'>Fehler: ";
                  switch($sens["errorcode"]){
                    case "100":
                    echo "Sensor nicht gefunden! ";
                    break;
                  }
                  echo "</div><br>";
                  echo "<div class='alert alert-info'>Fehlercode: <code>";
                  print_r($json_sens);
                  echo "</code></div>";

                }
              //$data = fopen("./api.php?action=readValue&sens=0x001&key=FucK4sch001","r");
              //print_r($data);
              ?>
            </div>
          </div>
          <div>
            <div class="w-row">
              <div id="chartContainer"></div>
<script type="text/javascript">
$(function () {
var chart = new CanvasJS.Chart("chartContainer", {
	theme: "theme2",
	zoomEnabled: true,
	animationEnabled: true,
	title: {
		text: "Temperature log"
	},
	subtitles:[
		{   text: "(Try Zooming & Panning)" }
	],
	data: [
	{
		type: "line",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}
	]
});
chart.render();
});
</script>
            </div>
          </div>
        </div>
      </div>
</div>
