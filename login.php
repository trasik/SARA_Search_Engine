<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SARA-Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style_res.css">
    <link rel="stylesheet" href="css/adminpanel.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  </head>
  <body>
    <div class="navbar" id="myNav">
      <a href="index.html" class="active">Home</a>
      <div class="dropdown">
        <button class="dropbtn">Course
          <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
          <a href="https://learn.zybooks.com/zybook/CUNYCSCI355TeitelmanSpring2019" target="_blank">Zybooks</a>
        </div>
      </div>
      <div class="dropdown">
        <button class="dropbtn">Search
          <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
          <a href="results.html">Phase 2: Fixed Results</a>
          <a href="results2.html">Phase 3: From File</a>
          <a href="results3.html">Phase 4: Google API</a>
          <a href="results4.php">Phase 5: Our Search Engine</a>
        </div>
      </div>
      <div class="dropdown">
        <button class="dropbtn">Browser
          <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
          <a href="browser.html#brinfo">Navigator</a>
          <a href="browser.html#winfo">Window</a>
          <a href="browser.html#sinfo">Screen</a>
          <a href="browser.html#linfo">Location</a>
          <a href="browser.html#geoinfo">Geolocation</a>
        </div>
      </div>
      <div class="dropdown">
        <button class="dropbtn">About
          <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
          <a href="about.html#about_info">About Us</a>
          <a href="about.html#contact_info">Contact Us</a>
        </div>
      </div>
      <a href="#">Admin</a>
      <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
    </div>
    <div class="container-login">
      <section class="login-info">
        <h2>Login</h2>
        <form class="login-form" action="" method="POST" autocomplete="off">
          <input type="text" class="login-control" name="user" placeholder="Username" required>
          <input type="password" class="login-control" name="pass" placeholder="Password" required>
          <input type="submit" id="lsubmit" name="lsubmit" value="Login">
        </form>
      </section>
    </div>
    <?php
      if (isset($_POST['lsubmit'])) {
        $user = $_POST['user'];
        $pass = $_POST['pass'];

        if (($user == 'trasik' && $pass == 'cs355') || ($user == 'ksingh' && $pass == 'cs355')
            || ($user == 'lteitelman' && $pass == 'cs355')) {
          session_start();
          $_SESSION['user'] = $user;
          header("Location: server/admin/dashboard.php");
          exit();
        } else {
          header("Location: login.php?error=wronguser&pass");
          exit();
        }
      }
    ?>
  </body>
</html>
