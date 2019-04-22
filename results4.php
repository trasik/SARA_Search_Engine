<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SARA-Search Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style_res.css">
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
          <a href="">Phase 5: Our Search Engine</a>
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
      <a href="login.php">Admin</a>
      <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
    </div>

    <div class="container-search">
      <section class="search">
        <form action ="" method="GET" autocomplete="off">
          <input autocomplete="off" type="text" id="src-text" class="input" name="query" placeholder="Type to Search...">
          <button type="submit" id="submit" class="btn-submit">
            <i class="fa fa-search"></i>
          </button>
        </form>
      </section>
    </div>
    <div class="container-srcres">
      <h1>Search Results</h1>
      <div id="up-down-btns">
        <button type="button" name="f-download" id="download" onclick="fileDownload()"><i class="fas fa-file-download"></i>  Download to</button>
        <select name="options" id="options">
          <option value="json">JSON</option>
          <option value="csv">CSV</option>
          <option value="xml">XML</option>
        </select>
      </div>
      <section class="src-res" id="results_info">
        <?php
          if(isset($_GET['query'])) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "saraEngine";

            $conn = new mysqli($servername, $username, $password, $dbname);
            $conn->set_charset("utf8");

            if ($conn->connect_error) {
              echo "ERROR: PLEASE CHECK ERROR PANEL";
              file_put_contents("admin/error.txt","Connection failed: " . $conn->connect_error);
              exit();
            }

            $sql = "CREATE TABLE IF NOT EXISTS wordtable (
            wordid INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            word VARCHAR(100) NOT NULL,
            dateadded TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )";

            if ($conn->query($sql) === FALSE) {
              echo "ERROR: PLEASE CHECK ERROR PANEL";
              file_put_contents("admin/error.txt","Error creating table: " . $conn->error);
            }

            $query = $_GET['query'];

            $q = "SELECT * FROM `wordtable` WHERE `word` = '{$query}'";
            $result = mysqli_query($conn, $q);
            $sql;
            if (mysqli_num_rows($result) > 0) {
              $stmt = "UPDATE `wordtable` SET word=? WHERE word=?";
              $sql = $conn->prepare($stmt);
              $sql->bind_param("ss", $query, $query);
              $sql->execute();
            } else {
              $sql = $conn->prepare("INSERT INTO `wordtable`(`word`) VALUES (?)");
              $sql->bind_param("s", $query);
              $sql->execute();
            }

            $terms = explode(" ", $query);
            $counter = 0;
            $qterm = "";
            foreach($terms as $term) {
              $counter++;
              if ($counter == 1) {
                $qterm .= "title LIKE '%$term%' OR description LIKE '%$term%'";
              } else {
                $qterm .= " AND title LIKE '%$term%' OR description LIKE '%$term%'";
              }
            }

            $sql = "SELECT * FROM pagetable WHERE $qterm";
            $results = $conn->query($sql);
            if ($results->num_rows == 0) {
              echo "<p id='results_num'>0 results found</p>";
            } else {
              echo "<p id='results_num'>" . $results->num_rows ." results found</p>";
            }
            $check = 0;
            foreach($results->fetch_all(MYSQLI_ASSOC) as $result) {
              $check++;
              echo "<br><input type='checkbox' class='page_check' id='check_" . $check . "'>";
              echo "<div class='page_item'><ul><li><h2>" . $result['title'] . "</h2></li>";
              echo "<li><a href='" . $result['url'] . "'>" . $result['url'] . "</a></li>";
              echo "<li>" . $result['description'] . "</li></ul></div>";
            }
          }
        ?>
      </section>
    </div>
    <script src="scripts/script.js"></script>
    <script src="scripts/FileSaver.js"></script>
  </body>
</html>
