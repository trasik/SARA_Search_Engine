<?php
  if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $mailFrom = $_POST['mail'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mailTo = "torendrarasik@gmail.com,ksin3798@gmail.com";
    $headers = "From: ".$mailFrom;
    $txt = "You have received a email from ".$name.".\n\n".$message;

    mail($mailTo, $subject, $txt, $headers);
    header("Location: about.html?mailsend");
  }
?>
