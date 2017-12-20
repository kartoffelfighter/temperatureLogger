<?php

// This api gets the data from clients and arduinos and puts it into the database

/*

@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@ Overall Template for Failure:
@ {"success":"false","errorcode":"xXX","description":"foobar"}
@
@ Registered errorcodes:
@ 0   =>    given API key is wrong
@
@ 10  =>    Email is already in use
@
@ 20  =>    The to the email belonging user was deleted from database
@ 21  =>    The given email does not exist in database, user can not be deleted
@
@ 100 => Sensor not found
@
@ mysql related error codes:
@ 900 =>    MYSQL Connection error
@ 910 =>    email query failed
@ 911 =>    register query failed
@
@
@ µC related:
@ 0x001 => write values to database query failed
@@ if the success of the given return is true, the function resultet positive.
@@ if the success of the given return is false, the function resultet negative.
@@ all errors and successes have an description in GERMAN. For other languages use the provided error-codes
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


*/


// hardcoded api key, hope nobody will find this
define("API_KEY", "FucK4sch001");
// database
define('DB_HOST', 'localhost');   //host
define('DB_USER', 'USER223310');  //user
define('DB_PASS', 'fishi95');     //pass
define('DB_NAME', 'templog');     //database
// debug class:
$datadebug = new stdClass();

// check for the api-key
if(filter_input(INPUT_GET, 'key') != API_KEY ){
  if(filter_input(INPUT_POST, 'data') != API_KEY){
  $return['success'] = "false";   // prepare error string
  $return['errorcode'] = "0";
  $return['description'] = "falscher api key";
  echo json_encode($return);
  die;
}
}

// link to database:

$mysqllink = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die($mysqllink->connect_error);   // Datenbankverbindung herstellen
if($mysqllink->connect_errno != "0") {    // Datenbankverbindung fehlgeschlagen!
  $return['success'] = "false";   // prepare error string
  $return['errorcode'] = "900";
  $return['description'] = "mysql-verbindung fehlgeschlagen";
  echo json_encode($return);
  echo $mysqllink->connect_error; // Fehlerausgabe
}
else {
  //echo "db connected! <br>";    // Datenbankverbindung hergestellt
//  echo $mysqllink->connect_error;
}

switch(filter_input(INPUT_GET, 'action')){    // API Calls
  case 'login':                                     // Function API-LOGIN
  if(filter_input(INPUT_GET, 'json')){              // json-methode (Daten kommen nicht blank sondern als JSON kodiert)
    echo "json login enabled! <br> apikey present";   // Debugausgabe
    echo "recieved json string: <b>";
    $data = filter_input(INPUT_GET, 'data');
    echo $data;
    echo "</b>  decoded: <b>";
    var_dump(json_decode($data));
    echo "</b>";
    }
    else {                                      // Daten kommen nicht als json
      $credits['email'] = $_POST['email'];      // $credits formatieren
      $credits['password'] = $_POST['password'];
      var_dump($credits);
      $jcredits = json_encode($credits);        // $credits json codieren
      echo $jcredits;
    }
  break;

  case 'register':

/*
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@     register API Call
@-----------------------------
@
@ register can handle to kinds of calls:
@ standard-post:
@ register expects a _POST value as following:
@ username, password, email
@-----------------------
@json:
@ Post a value named "credits" by following template:
@ {"username":"foo","password":"bar","email":"foobar@foobar";}
@
@-------------------------
@   returned value is always json
@   Template for success:
@   {"success":"true","newUserID":"35","newUserEmail":"test1@mail.com"}
@
@   Template for failure:
@   {"success":"false","errorcode":"10","description":"Email bereits belegt"}
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
*/
    if(filter_input(INPUT_GET, 'json')) {                 // JSON aktiviert, Daten kommen json-formatiert
      $credits = json_decode($_POST['credits'], true);
    }
    else {
      $credits['username'] = $_POST['username'];
      $credits['password'] = $_POST['password'];
      $credits['email'] = $_POST['email'];
    }
    if($credits == NULL || !isset($credits['username']) || !isset($credits['password']) || !isset($credits['email'])){

        $return['success'] = "false";   // prepare error string
        $return['errorcode'] = "101";
        $return['description'] = "Nicht alle ben&ouml;tigten Daten &uuml;bergeben! ";
        echo(json_encode($return));   // return error string

        }
      else {
          echo(generateNewUser(json_encode($credits))); // generate new user!
        }


  break;
  case 'writeValue':
  /*
  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
  @     writeValue API Call
  @-----------------------------
  @ writeValue handles calls from the wifi-sensor.
  @
  @------------------------------
  @ Example Call:
  @ ?action=writeValue&data={"sensid":"0x001","temp":"0x014","humid":"0x063","comment":"0x00"}
  @-----------------------
  @json:
  @ Post a value named "credits" by following template:
  @ {"sensid":"[HEX]0x001","temp":"[HEX]0x014","humid":"[HEX]0x063","comment":"[HEX]0x00"}
  @
  @-------------------------
  @ writeValue return a json string:
  @ if the string fits and all values are passed:
  @ {"success":"true"}
  @ if the string misfits
  @ {"success":"false","errorcode":"0x001"} // returnes hex-codes to decode on µC
  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
  */

  $data = filter_input(INPUT_GET, 'data');
  echo(writeValue($data));
  break;


  case 'readValue':
  /*
  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
  @     readValue API Call
  @-----------------------------
  @ readValue handles calls from the wifi-sensor.
  @
  @------------------------------
  @ Example Call:
  @ ?action=readValue&sens=0x001
  @-----------------------
  @ blank/html _GET
  @-------------------------
  @ writeValue return a json string:
  @
  @ returns json like {"success":"true", "data":[{"id":"1","sensor":"0x001","temp":"0x014","humid":"0x063","time":"1512323121","comment":"0x00"},....]}
  @
  @
  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
  */
  $data = filter_input(INPUT_GET, 'sens');
  echo(readValue($data));
  break;

  case "handshake":

  /*
  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
  @ Handshake with sensor
  @ Sensor sends nothing to api (just the call)
  @ Server returns true & current server time in unix-format
  @-------------------------
  @ json return string format:
  @ {"success":"true","time":"1512751312"}
  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
  */
  $handshake["success"] = "true";
  $handshake["time"] = date("U");
  echo json_encode($handshake);
  break;

  case 'testing':                                             // test-case
  $data = json_decode(filter_input(INPUT_GET, 'data'));
  var_dump($data);
  var_dump(getxy($data->sens, $data->value));
  break;

}


function generateNewUser($data) {     // Neuer Nutzer anlegen ($data = JSON STring mit name = *, password = *, email = *)
  global $mysqllink;
  $data = json_decode($data);   // transform json into obj
  if(!$query = $mysqllink->query("SELECT * FROM `users` WHERE `email` = '".$data->email."'")){    // query, if email is already present

    $return['success'] = "false";     // prepare error string
    $return['errorcode'] = "910";
    $return['description'] = "Emailquery konnte nicht mit Datenbank ausgemacht werden";

    return(json_encode($return));   // return error string
  }
  else {
    if($query->num_rows != 0){      // check if email is alredy present
      $return['success'] = "false";   // prepare error string
      $return['errorcode'] = "10";
      $return['description'] = "Email bereits belegt";
      return json_encode($return);  //return error string
    }
    else {
      $pw = password_hash($data->password, PASSWORD_DEFAULT);   // hash password
      if($mysqllink->query("INSERT INTO `users`(`email`,`password`,`name` ) VALUES ( '".$data->email."', '".$pw."', '".$data->name."' )"))    {   // insert user values into database

        $UserID = $mysqllink->query("SELECT * FROM `users` WHERE `email` = '" . $data->email . "'");    // query for determinate the UserID
        $UID = $UserID->fetch_object(); //fetch obj from query above

        $return['success'] = "true";    // prepare success string
        $return['newUserID'] = $UID->id;
        $return['newUserEmail'] = $data->email;
        //$return['newUserPassword'] = $pw;   do NOT return passowrds!
        return json_encode($return);    // return success string
        }
            else {
              $return['success'] = "false";     // prepare error string
              $return['errorcode'] = "911";
              $return['description'] = "Registerquery Fehlgeschlagen";
              return json_encode($return); // return error string
            };
          };
        };
      }

function deleteUser($data){     // delete existing user     ($data = JSON String mit email = *)
  global $mysqllink;    // use the global mysqllink

  $data = json_decode($data);
  $query = $mysqllink->query("SELECT * FROM `users` WHERE `email` = '" . $data->email . "'");  // check, if user is existing
  if($query->num_rows != 0){    // if yes, then:
    $found = $query->fetch_object();  // fetch obj
      if($mysqllink->query("DELETE FROM `users` WHERE `email` = '".$data->email."'" )){    // Lösche den Nutzer

      $return['success'] = "true";     // prepare error string
      $return['errorcode'] = "20";
      $return['description'] = "Nutzer gel&ouml;scht";
      return json_encode($return);

    }
  }
  else {
    $return['success'] = "false";     // prepare error string
    $return['errorcode'] = "21";
    $return['description'] = "Zu löschender Nutzer existiert nicht!";
    return json_encode($return);
  }
}

function login($data){

}

function writeValue($data){    // json-string input with (sensID => 0x00; temp =>0x000; humid => 0x000; comment => string())
  global $mysqllink;
  $data = json_decode($data);
  //var_dump($data);
  //var_dump(date("U"));
  if($mysqllink->query("INSERT INTO `sens`(`sensor`,`temp`,`humid`, `time`, `comment`, `accu` ) VALUES ( '".$data->sensid."', '".$data->temp."', '".$data->humid."', '".date("U")."', '".$data->comment."', '".$data->accu."' )"))    {
  $return['success'] = "true";    // prepare success string
  return json_encode($return);    // return success string
}
else {
  $return['success'] = "false";     // prepare error string
  $return['errorcode'] = "0x001";   // query failed
  return json_encode($return);
}
}

function readValue($data){
  global $mysqllink;

    //var_dump($data);

    $query = $mysqllink->query("SELECT * FROM `sens` WHERE `sensor` = '".$data."' ");  // check, if user is existing

    if($query->num_rows != 0){

      while($found = $query->fetch_object()){
        $founds[] = $found;
      }
      //var_dump($founds);
      //  var_dump($found);

      $return['success'] = "true";
      $return['data'] = $founds;

      return(json_encode($return));
    }
    else {
      $return['success'] = "false";
      $return["errorcode"] = "100";
      $return["description"] = "Sensor not found!";

      return(json_encode($return));
    }

  }
 ?>
