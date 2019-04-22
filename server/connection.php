<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "saraEngine";

  $conn = new mysqli($servername, $username, $password);
  $conn->set_charset("utf8");

  if ($conn->connect_error) {
    echo "ERROR: PLEASE CHECK ERROR PANEL";
    file_put_contents("admin/error.txt","Connection failed: " . $conn->connect_error);
    exit();
  }

  $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
  if ($conn->query($sql) === TRUE) {
    echo "Connected to Database successfully<br>";
  } else {
    echo "ERROR: PLEASE CHECK ERROR PANEL";
    file_put_contents("admin/error.txt","Error creating database: " . $conn->error);
  }

  $conn = new mysqli($servername, $username, $password, $dbname);
  $conn->set_charset("utf8");

  if ($conn->connect_error) {
    echo "ERROR: PLEASE CHECK ERROR PANEL";
    file_put_contents("admin/error.txt","Connection failed: " . $conn->connect_error);
    exit();
  }

  $sql = "CREATE TABLE IF NOT EXISTS pagetable (
  pageid INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  description VARCHAR(400) NOT NULL,
  url VARCHAR(150),
  lastindexed TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  )";

  if ($conn->query($sql) === TRUE) {
    echo "Connected to Table 'pagetable' successfully<br>";
  } else {
    echo "ERROR: PLEASE CHECK ERROR PANEL";
    file_put_contents("admin/error.txt","Error creating table: " . $conn->error);
  }
?>
