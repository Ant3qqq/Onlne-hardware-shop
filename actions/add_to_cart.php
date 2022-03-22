<?php
session_start();

if (!empty($_SESSION['cart_content'])) {
  echo "NIE pusta sesja";
    $cart_session=$_SESSION['cart_content'];
}
// else {
//   echo "pusta sesja";
//     $_SESSION['cart_content']["$_GET[product_id]"] = intval($_GET['amount']) ;
//     echo "<br>content po stworzeniu sesji do zera<br>";
//   var_dump($_SESSION['cart_content']);
// }

if(isset($cart_session[$_GET['product_id']])){
  echo "to id jest w sesji";
  $cart_session[$_GET['product_id']] = intval($cart_session[$_GET['product_id']]) + $_GET['amount'];
  $_SESSION['cart_content']=$cart_session;

}else {
  echo "to id NIE jest w sesji";
  $cart_session[$_GET['product_id']] = intval($_GET['amount']);
  $_SESSION['cart_content']=$cart_session;
}
echo "<hr>";
var_dump($_SESSION['cart_content']);
echo "<hr>";
print_r($_SESSION['cart_content']);
echo "<a href=../pages/main.php>Sklep</a>";
 ?>
