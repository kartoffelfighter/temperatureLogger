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