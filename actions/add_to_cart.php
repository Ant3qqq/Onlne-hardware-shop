<?php
session_start();

if (!empty($_SESSION['cart_content']["$_GET[product_id]"])) {
  echo "NIE pusta sesja";
  $_SESSION['cart_content']["$_GET[product_id]"] = intval($_GET['amount']) + $_SESSION['cart_content']["$_GET[product_id]"];
}

else {
  echo "pusta sesja";
    $_SESSION['cart_content']["$_GET[product_id]"] = intval($_GET['amount']) ;
    echo "<br>content po stworzeniu sesji do zera<br>";
  var_dump($_SESSION['cart_content']);
}

header("location: ../pages/shopping_cart_page.php");
?>
