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