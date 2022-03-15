<?php
session_start();

$con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
$sql = "SELECT product_id, name, amount, price, image_name FROM `products` where product_id = '$_GET[id]';";
$res=$con->query($sql);
$x=$res->fetch_array();

$sql = "delete FROM `products` where product_id = '$_GET[id]';";
$con->query($sql);

header("location: ../pages/products_management_page.php?information=Usunięto towar o id: '$_GET[id]', nazwie:' $x[1]', ilości: '$x[2]', cenie: '$x[3]' i zdięciu: '$x[4]'");

 ?>
