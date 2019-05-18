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
      <a href="server/admin/dashboard.php">Admin</a>
      <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
    </div>

    <div class="container-search">
      <section class="search">
        <form action ="" method="GET" autocomplete="off">
          <input autocomplete="off" type="text" id="src-text" name="query" placeholder="Type to Search...">
          <button type="submit" id="submit" class="btn-submit">
            <i class="fa fa-search"></i>
          </button><br>
          <label class="checkContainer">Default
            <input type="radio" name="checkOpt" value="default" checked="checked" <?php if (isset($_GET['checkOpt']) && $_GET['checkOpt'] == 'default')  echo ' checked="checked"';?>>
            <span class="checkmark"></span>
          </label>
          <label class="checkContainer">Check-Insensitive
            <input type="radio" name="checkOpt" value="case" <?php if (isset($_GET['checkOpt']) && $_GET['checkOpt'] == 'case')  echo ' checked="checked"';?>>
            <span class="checkmark"></span>
          </label>
          <label class="checkContainer">Partial Word
            <input type="radio" name="checkOpt" value="partial" <?php if (isset($_GET['checkOpt']) && $_GET['checkOpt'] == 'partial')  echo ' checked="checked"';?>>
            <span class="checkmark"></span>
          </label>
          <label class="checkContainer">Both
            <input type="radio" name="checkOpt" value="both" <?php if (isset($_GET['checkOpt']) && $_GET['checkOpt'] == 'both')  echo ' checked="checked"';?>>
            <span class="checkmark"></span>
          </label>
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
          require 'server/connection.php';

          if(isset($_GET['query'])) {
            $query = $_GET['query'];
            $sql;
            $checkOpt;
            if(isset($_GET['checkOpt'])) {
              $checkOpt = $_GET['checkOpt'];
              if ($_GET['checkOpt'] == 'default') {
                $sql = "SELECT * FROM `page`, `word`, `page_word`
                          WHERE page.pageid = page_word.pageid
                          AND word.wordid = page_word.wordid
                          AND word.wordName = '$query'
                          ORDER BY page_word.freq_wordcount DESC
                          ";
              } else if($_GET['checkOpt'] == 'case') {
                $sql = "SELECT * FROM `page`, `word`, `page_word`
                          WHERE page.pageid = page_word.pageid
                          AND word.wordid = page_word.wordid
                          AND LOWER(word.wordName) = LOWER('$query')
                          ORDER BY page_word.freq_wordcount DESC
                          ";
              } else if($_GET['checkOpt'] == 'partial') {
                $sql = "SELECT * FROM `page`, `word`, `page_word`
                          WHERE page.pageid = page_word.pageid
                          AND word.wordid = page_word.wordid
                          AND word.wordName LIKE '%$query%'
                          ORDER BY page_word.freq_wordcount DESC
                          ";
              } else if ($_GET['checkOpt'] == 'both') {
                $sql = "SELECT * FROM `page`, `word`, `page_word`
                          WHERE page.pageid = page_word.pageid
                          AND word.wordid = page_word.wordid
                          AND LOWER(word.wordName) LIKE LOWER('%$query%')
                          ORDER BY page_word.freq_wordcount DESC
                          ";
              }
            }
            $start = microtime(true);
            $result = mysqli_query($conn, $sql);
            $total = $result->num_rows;
            $perPage = 10;
            $totalPages = ceil($total/$perPage);
            $page;
            if(isset($_GET['page'])) {
              $page = $_GET['page'];
            } else {
              $page = 1;
            }
            $offset = ($page - 1) * $perPage;
            $sql = $sql. "LIMIT $perPage OFFSET $offset";
            $result = mysqli_query($conn, $sql);
            if ($result->num_rows > 0) {
              $c = 0;
              echo "<div id='selections'>
                <button type='button' id='select-1' class='select-btn'>Select All</button>
                <button type='button' id='select-2' class='select-btn'>Deselect All</button>
              </div><br>";
              echo "<p id='resultCount'>Page $page of $total results</p>";
              while($row = $result->fetch_assoc()) {
                $c++;
                echo "<br>\n<input type='checkbox' class='page_check' id='check_" . $c . "'>\n<div class='page_item'>\n<ul>\n<li><h2>" . $row["title"] . "</h2></li>\n";
                echo "<li><a href='" . $row["url"] . "'>" . $row["url"] . "</a></li>\n";
                echo "<li>" . $row["description"] . "</li>\n</ul>\n</div>";
              }
              echo "<br><footer id='pagelist'>";
              for($i = 1; $i <= $totalPages; $i++) {
                if($i == $page) {
                  echo "<a class='page_active'>" . $i ."</a>";
                } else {
                  echo "<a href='results4.php?query=$query&checkOpt=$checkOpt&page=$i'.'>$i</a>'";
                }
              }
              echo "</footer";
            } else {
              echo "<p id='resultCount'>0 Results</p>";
            }
            $finish = microtime(true) - $start;
            $sql = "INSERT INTO `search` (terms, count, `option`, pageNum, timeToSearch) VALUES ('$query', $total,'$checkOpt', $page, $finish)";
            mysqli_query($conn, $sql);
          }
        ?>
      </section>
    </div>
    <script src="scripts/script.js"></script>
    <script src="scripts/FileSaver.js"></script>
  </body>
</html>
