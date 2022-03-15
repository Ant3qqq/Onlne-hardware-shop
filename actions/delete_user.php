<?php
session_start();

$con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
$sql = "SELECT id, name, surname, birthday, email_address, home_address, user_type FROM `users` where id = '$_GET[id]';";
$res=$con->query($sql);
$x=$res->fetch_array();

$sql = "delete FROM `users` where id = '$_GET[id]';";
$con->query($sql);

header("location: ../pages/user_management_page.php?information=Usunięto użytkownika o id: '$_GET[id]', imieniu:' $x[1]', nazwisku: '$x[2]', dniu urodzin: '$x[3]', adresie email: '$x[4]', adresie zamieszkania: '$x[5]' i typie: '$x[6]' ");

?>
