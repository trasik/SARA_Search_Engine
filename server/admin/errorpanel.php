<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SARA-Error Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../css/adminpanel.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  </head>
  <body>
    <ul>
      <li><h2>Administration Panel</h2></li>
      <li><a href="../../index.html">Home</a></li>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="indexpanel.php">Indexer Panel</a></li>
      <li><a href="#">Error Panel</a></li>
      <li><a href="searchpanel.php">Search Panel</a></li>
    </ul>

    <div class="container">
      <section class="error_info">
        <h2>Error Panel</h2>
        <div class="error_prompt">
          <?php
          $myfile = fopen("error.txt", "r") or die("Unable to open file!");
          if (filesize("error.txt") == 0) {
            echo "No errors reported!";
            exit();
          } else {
            while(!feof($myfile)) {
              echo fgets($myfile) . "<br>";
            }
            fclose($myfile);
          }
          ?>
        </div>
      </section>
    </div>
  </body>
</html>
