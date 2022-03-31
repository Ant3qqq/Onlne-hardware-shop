<?php
session_start();

if (empty($_GET['name'])) {
  header("location: ../pages/products_management_page.php?information=Błąd!!! nie można dodać produktu bez nazwy!");
}else {
    if (empty($_GET['amount'])) {
      $_GET['amount']=0;
    }
    if (empty($_GET['price'])) {
      $_GET['price']=0;
    }
    if (empty($_GET['image_name'])) {
      $_GET['image_name']='default.png';
    }

    $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
    $sql = "INSERT INTO `products` (`product_id`, `name`, `amount`, `price`, `image_name`,`visibility`) VALUES (NULL, '$_GET[name]', '$_GET[amount]', '$_GET[price]', '$_GET[image_name]','$_GET[visibility]' );";
    $con->query($sql);

    header("location: ../pages/products_management_page.php?information=Dodano produkt o następujących danych - nazwa: $_GET[name], ilość:  $_GET[amount], cena: $_GET[price], zdięcie: $_GET[image_name], widoczność: $_GET[visibility].");
  }
$con->cloes();
 ?>
