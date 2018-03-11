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
