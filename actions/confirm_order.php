<?php
session_start();

// status
// 0 - potwierdzenie wysłane na maila
// 1 - zamówienie w realizacji
// 2 - zamówienie w drodze
// 3 - dostarczono
// 4 - rozpoczęto procedurę zwrotu
// 5 - zwrócono

$con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

// wrzucanie danych do tabeli orders
date_default_timezone_set("Europe/Warsaw");
$date_time = date("Y-m-d H:i:s");

$sql = "INSERT INTO `orders` (`order_id`, `user_id`, `date_time`, `status`) VALUES (NULL, '$_SESSION[user_id]', '$date_time', 0);";
$con->query($sql);

// POMYSŁ -  nadawać id typu 2022/03/30/123 123- nr zamówienia, id na stringa i z łapy dawać

// pobranie ostatniego (najwyżeszgo) order id nadanego automatycznie w bazie danych
$sql = "SELECT max(order_id) as order_id FROM `orders` ";
$res=$con->query($sql);
$x=$res->fetch_assoc();
$order_id=$x['order_id'];



foreach ($_SESSION['cart_content'] as $key => $value) {
  // pobranie aktualnej ceny produktu
  $sql = "SELECT price FROM products where product_id = $key ";
  $res=$con->query($sql);
  $y=$res->fetch_assoc();
  $price_of_product=$y['price'];

  // wstawia dane do tabeli ordered products
  $sql = "INSERT INTO `ordered_products` (`order_id`, `product_id`, `amount`, `price`) VALUES ('$order_id', '$key', '$value', '$price_of_product');";
  $con->query($sql);

  // pobiera ilośći produktów na stanie
  $sql = "SELECT amount FROM `products` where product_id = $key";
  $res=$con->query($sql);
  $res_array = $res->fetch_assoc();
  $amount = $res_array['amount']-$value;

  // usuwa dane ilośći ze stanu sklepowego
  $sql = "UPDATE `products` SET `amount` = $amount WHERE `products`.`product_id` = $key;";
  $con->query($sql);

}

// usuwa wszytko z sesji koszyka i wyświetla potwierdzenie
unset($_SESSION["cart_content"]);
header("location: ../pages/order_confirmation_page.php?information=Zamówienie nr: $x[order_id] zostało przekazane do realizacji, dziękujemy za skorzystanie z naszych usług");


$con->cloes();
 ?>
