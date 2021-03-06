<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SARA-Indexer Panel</title>
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
      <li><a href="#">Indexer Panel</a></li>
      <li><a href="errorpanel.php">Error Panel</a></li>
      <li><a href="searchpanel.php">Search Panel</a></li>
    </ul>

    <div class="container">
      <section class="index_info">
        <h2>Indexer Panel</h2>
        <?php
          if (isset($_GET['error'])) {
            if($_GET['error'] == "emptydepth") {
              echo "<p class='error'>Invalid Depth: Depth Field Is Empty!</p>";
            } else if($_GET['error'] == "invaliddepth") {
              echo "<p class='error'>Invalid Depth: Cannot Exceed 5!</p>";
            }
          }
        ?>
        <form class="parser-form" action="" method="POST" autocomplete="off">
          <input type="text" class="form-parse" name="name" placeholder="Enter a URL to Begin..." required>
          <input type="radio" name="parseOpt" value="single" id="radio-one" class="form-radio" checked>
          <label for="radio-one">Index Single Page</label></br></br>
          <input type="radio" name="parseOpt" value="multiple" id="radio-two" class="form-radio">
          <label for="radio-two">Index Multiple Pages</label>
          <input type="number" min="0" step="1" class="form-depth" name="depth" placeholder="Enter a depth"></br></br>
          <button type="submit" id="psubmit" name="psubmit" <?php echo isset($_POST["psbmit"]) ? "disabled" : "";?>>Start Indexer</button>
        </form>
        <div class="results_prompt">
          <?php include '../indexer.php' ?>
        </div>
      </section>
      <div class="indexerTable">
        <h2>Indexer History</h2>
        <?php
        require '../connection.php';

        $sql = "SELECT * FROM indexer ORDER BY dateAdded DESC";
        $result = mysqli_query($conn, $sql);

        echo "<table border='1'>
        <tr>
        <th>Indexer ID</th>
        <th>Base URL</th>
        <th>Option</th>
        <th>Total Words Inserted</th>
        <th>Total URL's Inserted</th>
        <th>Total Time (Seconds)</th>
        <th>Indexed Date</th>
        </tr>";

        while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['indexerId'] . "</td>";
          echo "<td>" . $row['baseUrl'] . "</td>";
          echo "<td>" . $row['option'] . "</td>";
          echo "<td>" . $row['totalCount'] . "</td>";
          echo "<td>" . $row['totalLinks'] . "</td>";
          echo "<td>" . $row['totalTime'] . "</td>";
          echo "<td>" . $row['dateAdded'] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
        ?>
      </div>
    </div>
  </body>
</html>
