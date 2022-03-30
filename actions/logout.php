
<?php
  session_start();

  echo "wyloguj";
  print_r($_SESSION);
  unset($_SESSION["name_logged"]);
  unset($_SESSION["surname_logged"]);
  unset($_SESSION["email_address_logged"]);
  unset($_SESSION["cart_content"]);
  unset($_SESSION['user_id']);

  header("location: ../pages/main.php");
 ?>
