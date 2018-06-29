<?php
error_reporting(-1);
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="marc">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
  </head>

  <body>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

     <nav class="navbar navbar-expand-lg navbar-darj bg-dark sticky-top">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto right">
          <li class="nav-item active">
            <a class="nav-link" href="?graph">Dashboard <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?api">API Documentation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?display">Display</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Login</a>
          </li>
        </ul>
      </div>
    </nav>
    <?php
// Temperaturelogger in Marcs Bude


$pages = array(
  "home" => "./style/index.html",
  "graph" => "./style/graph.php",
  "display" => "./style/display.php",
  "api" => "./style/api.php"      //this is just the api description, not the API itself!
);


if(!empty($_GET)){
  foreach($_GET as $key => $value){
    include($pages[$key]);
  }
}
else {
  include($pages["home"]);
}


 ?>
</body>
</html>
=======
<?php
//$request = explode('?', trim($_SERVER['PATH_INFO'],'?'));


//var_dump($_SERVER['PATH_INFO']);

//$request = filter_input(INPUT_GET, 'home');
//var_dump($request);
 ?>
<!DOCTYPE html>
<!--  This site was created in Webflow. http://www.webflow.com  -->
<!--  Last Published: Sun Nov 26 2017 16:31:34 GMT+0000 (UTC)  -->
<html data-wf-page="5a1ae07245a0eb000187da02" data-wf-site="5a1ae07245a0eb000187da01">
<head>
  <meta charset="utf-8">
  <title>Marcs dingens</title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="Marci" name="generator">
  <link href="./css/normalize.css" rel="stylesheet" type="text/css">
  <link href="./css/webflow.css" rel="stylesheet" type="text/css">
  <link href="./css/marcs-dingens.webflow.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js" type="text/javascript"></script>
  <script type="text/javascript">WebFont.load({  google: {    families: ["Ubuntu:300,300italic,400,400italic,500,500italic,700,700italic"]  }});</script>
  <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
  <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<body>
  <div class="div-block-5"></div>
    <div class="div-block-6"></div>
    <div>
      <div data-collapse="none" data-animation="default" data-duration="400" class="navbar w-nav">
        <div class="w-clearfix">
          <nav role="navigation" class="nav-menu w-nav-menu"><a href="?home" class="navlink w-nav-link">Home</a><a href="?graph" class="navlink w-nav-link">Graph</a><a href="?api" class="navlink w-nav-link">API description</a></nav>
          <div class="w-nav-button">
            <div class="w-icon-nav-menu"></div>
          </div>
        </div>
      </div>
    </div>
<?php
// Temperaturelogger in Marcs Bude
// Version 1

// Datenbank:
// INT auto_inc         -> unixtime ->  JSON_String                                               // example table content type
// universalMeasuremtID -> time     ->  Measured Data                                             // example table names
// 00                   -> 1        ->  {"roomname":"1","temperature":"255","humidity":"0"}       // example db content



// Variables:


// include directory / style / login.html (login content)
//include( __dir__ . "/style/index.html");


$pages = array(
  "home" => "./style/index.html",
  "graph" => "./style/graph.php",
  "api" => "./style/api.php"      //this is just the api description, not the API itself!
);


//if($_GET['home']) {include $pages['home']}
//var_dump($pages);

if(!empty($_GET)){
  foreach($_GET as $key => $value){
    //var_dump($key);
    //var_dump($value);

    include($pages[$key]);

  }
}
else {
  include($pages["home"]);
}


 ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>
<script src="js/webflow.js" type="text/javascript"></script>
<!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
</body>
</html>
