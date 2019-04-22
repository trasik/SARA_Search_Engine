<?php
  if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $mailFrom = $_POST['mail'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if (!preg_match("/^[a-zA-Z ]*$/",$name) && !filter_var($mailFrom, FILTER_VALIDATE_EMAIL)) {
      header("Location: ../about.html?error=invalidemail&name");
      exit();
    } else if (!filter_var($mailFrom, FILTER_VALIDATE_EMAIL)) {
      header("Location: ../about.html?error=invalidemail");
      exit();
    } else if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      header("Location: ../about.html?error=invalidname");
      exit();
    } else {
      $mailTo = "torendrarasik@gmail.com,ksin3798@gmail.com";
      $headers = "From: ".$mailFrom;
      $txt = "You have received a email from ".$name.".\n\n".$message;

      mail($mailTo, $subject, $txt, $headers);
      header("Location: ../about.html?mailsend");
    }
  }
?>
