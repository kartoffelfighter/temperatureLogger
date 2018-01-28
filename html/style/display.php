<?php
include ( __DIR__ . '/settings.php');
$dataPoints = array();

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
var_dump($sens->data[0]);

echo "<table><thead><tr><th>Sens</th><th>temp</th><th>humid</th><th>time</th><th>comment</th><th>accu</th></tr></thead>";
foreach($sens->data as $row) {
  echo "<tr>";
  $unit = NULL;
  $comment = NULL;
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
      case "time":
      $unit = NULL;
      break;
    }
    echo "<td>" . $val . $unit . $comment . "<td>";
  }
  echo "</tr>";
}

/*
for($i = 0; i <= count($sens["data"])-1; $i++){
var_dump($sens["data"][$i]);
//foreach($sens["data"][$i] as list($id, $sensor, $temp, $humid, $time, $comment, $accu)){
//echo "messID: $id Sensor: $sensor Temp: $temp humid: $humid time: $time";
//}
}

*/