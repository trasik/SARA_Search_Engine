<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SARA-Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../css/adminpanel.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  </head>
  <body>
    <ul>
      <li><h2>Administration Panel</h2></li>
      <?php
        if (isset($_SESSION['user'])) {
          echo '<li><p>'.$_SESSION['user'].' is logged in</p></li>';
        }
      ?>
      <li><a href="#">Dashboard</a></li>
      <li><a href="indexpanel.php">Indexer Panel</a></li>
      <li><a href="errorpanel.php">Error Panel</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>

    <div class="container">
      <section class="dash">
        <h2>Dashboard - Under Construction</h2>
      </section>
    </div>
  </body>
</html>
