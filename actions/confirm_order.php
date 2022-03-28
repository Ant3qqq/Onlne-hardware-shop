<?php
session_start();

// status
// 0 - potwierdzenie wysłane na maila
// 1 - zamówienie w realizacji
// 2 - zamówienie w drodze
// 3 - dostarczono
// 4 - rozpoczęto procedurę zwrotu
// 5 - zwrócono



print_r($_SESSION['cart_content']);

$con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

// id usera - pobranie z bazy
$sql = "SELECT user_id, home_address  FROM `users` where email_address like $_SESSION['email_address_logged'];";
$res=$con->query($sql);
$x = $res->fetch_assoc();

// wrzucanie danych tabeli orders
$sql = "INSERT INTO `orders` (`order_id`, `user_id`, `date_time`, `price`, `shipping_address`, `status`) VALUES (NULL, '$x[user_id]', null, '$_GET[cart_price]', '$x[home_address]', '0');";


// dane do tabeli ordered products


$res=$con->query($sql);
$con->close();
$x=$res->fetch_assoc();



 ?>
