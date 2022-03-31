<?php
session_start();

if (!empty($_SESSION['cart_content']["$_GET[product_id]"])) {
  // ilość danego towaru jest większa od 0 lub w ogóle istnieje

  if (intval($_GET['amount']) + $_SESSION['cart_content']["$_GET[product_id]"]==0) {
    // jak zeruje ilość produktu to wyjeb go z sesji (i tym samym z koszyka)
    unset($_SESSION['cart_content']["$_GET[product_id]"]);
    header("location: ../pages/shopping_cart_page.php");
  }else {
    $_SESSION['cart_content']["$_GET[product_id]"] = intval($_GET['amount']) + $_SESSION['cart_content']["$_GET[product_id]"];
  }

}
else {
  $_SESSION['cart_content']["$_GET[product_id]"] = intval($_GET['amount']);
}

if ($_GET['src']=='shopping_cart_page') {
  header("location: ../pages/shopping_cart_page.php");
}else {
  header("location: ../pages/main.php");
}
$con->cloes();
?>
