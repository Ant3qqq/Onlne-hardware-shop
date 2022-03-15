
<?php
  session_start();

  echo "wyloguj";
  print_r($_SESSION);
  unset($_SESSION["name_logged"]);
  unset($_SESSION["surname_logged"]);
  unset($_SESSION["email_address_logged"]);
  print_r($_SESSION);
  header("location: ../pages/main.php");
 ?>
