<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SARA-Admin</title>
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
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="#">Indexer Panel</a></li>
      <li><a href="errorpanel.php">Error Panel</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>

    <div class="container">
      <section class="index_info">
        <h2>Indexer Panel</h2>
        <form class="parser-form" action="" method="POST" autocomplete="off">
          <input type="text" class="form-parse" name="name" placeholder="Enter a URL to Begin..." required>
          <input type="radio" name="parseOpt" value="single" id="radio-one" class="form-radio" checked>
          <label for="radio-one">Index Single Page</label></br></br>
          <input type="radio" name="parseOpt" value="multiple" id="radio-two" class="form-radio">
          <label for="radio-two">Index Multiple Pages</label>
          <input type="number" min="0" step="1" class="form-time" name="time" placeholder="Enter a time limit(seconds)"></br></br>
          <input type="submit" id="psubmit" name="psubmit" value="Start Parser">
        </form>
        <div class="results_prompt">
          <?php include '../indexer.php' ?>
        </div>
      </section>
    </div>
  </body>
</html>
