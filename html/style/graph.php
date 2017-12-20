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
              <div class="w-col w-col-6"><img src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg" class="image-4"></div>
              <div class="w-col w-col-6"><img src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg" class="image-2"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
