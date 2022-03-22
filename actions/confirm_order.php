<?php
session_start();
// wrzucić do bazy danych zamówienie

$con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
$sql = "";
$res=$con->query($sql);
$con->close();
$x=$res->fetch_assoc();



print_r($_SESSION);
 ?>
