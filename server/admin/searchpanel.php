<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SARA-Search Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../css/adminpanel.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  </head>
  <body>
    <ul>
      <li><h2>Administration Panel</h2></li>
      <li><a href="../../index.html">Home</a></li>
      <li><a href="#">Dashboard</a></li>
      <li><a href="indexpanel.php">Indexer Panel</a></li>
      <li><a href="errorpanel.php">Error Panel</a></li>
      <li><a href="#">Search Panel</a></li>
    </ul>
    <div class="container">
      <section class="dash">
        <h2>Search Panel</h2>
        <?php
        require '../connection.php';

        $sql = "SELECT * FROM search ORDER BY searchDate DESC";
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
        $sql = $sql. " LIMIT $perPage OFFSET $offset";
        $result = mysqli_query($conn, $sql);

        echo "<table border='1'>
        <tr>
        <th>Search ID</th>
        <th>Terms</th>
        <th>Count</th>
        <th>Option</th>
        <th>Page Number</th>
        <th>Search Date</th>
        <th>Time To Search (Seconds)</th>
        </tr>";

        while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['searchId'] . "</td>";
          echo "<td>" . $row['terms'] . "</td>";
          echo "<td>" . $row['count'] . "</td>";
          echo "<td>" . $row['option'] . "</td>";
          echo "<td>" . $row['pageNum'] . "</td>";
          echo "<td>" . $row['searchDate'] . "</td>";
          echo "<td>" . $row['timeToSearch'] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
        echo "<footer id='pagelist'>";
        for($i = 1; $i <= $totalPages; $i++) {
          if($i == $page) {
            echo "<a class='page_active'>" . $i ."</a>";
          } else {
              echo "<a href='searchpanel.php?page=$i'.'>$i</a>'";
          }
        }
        echo "</footer";
        ?>
      </section>
    </div>
  </body>
</html>
