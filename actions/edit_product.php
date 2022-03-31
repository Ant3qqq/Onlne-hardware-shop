<?php
session_start();


$con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

$sql = "SELECT product_id, name, amount, price, image_name,visibility FROM `products` where product_id = '$_GET[product_id]';";
$res=$con->query($sql);
$x=$res->fetch_assoc();

if (empty($_GET['name'])) {
  $_GET['name']=$x['name'];
}
if (empty($_GET['amount'])) {
  $_GET['amount']=$x['amount'];
}
if (empty($_GET['price'])) {
  $_GET['price']=$x['price'];
}
if (empty($_GET['image_name'])) {
  $_GET['image_name']=$x['image_name'];
}
if (!isset($_GET['visibility'])) {
  $_GET['visibility']=$x['visibility'];
}

$sql = "UPDATE `products` SET `name` = '$_GET[name]', `amount` = '$_GET[amount]', `price` = '$_GET[price]', `image_name` = '$_GET[image_name]',  `visibility` = '$_GET[visibility]' WHERE `products`.`product_id` = '$_GET[product_id]';";
$con->query($sql);
$con->cloes();

header("location: ../pages/products_management_page.php?information=Podsumowanie edycji produktu: id(stałe):$_GET[product_id], nazwa: $x[name] -> $_GET[name], ilość: $x[amount] -> $_GET[amount], <br>cena: $x[price] -> $_GET[price], zdięcie: $x[image_name] -> $_GET[image_name], widoczność: $x[visibility] -> $_GET[visibility]");


 ?>
