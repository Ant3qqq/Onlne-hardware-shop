<?php
session_start();


$con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

$sql = "SELECT user_id, name, surname, birthday, email_address,password, home_address, user_type FROM `users` where user_id = '$_POST[user_id]';";
$res=$con->query($sql);
$x=$res->fetch_assoc();

if (empty($_POST['name'])) {
  $_POST['name']=$x['name'];
}
if (empty($_POST['surname'])) {
  $_POST['surname']=$x['surname'];
}
if (empty($_POST['birthday'])) {
  $_POST['birthday']=$x['birthday'];
}
if (empty($_POST['email_address'])) {
  $_POST['email_address']=$x['email_address'];
}
if (empty($_POST['password'])) {
  $hash=$x['password'];
}else {
  $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
  if (password_verify($hash, $_POST['password'])) {
    header("location: ../pages/user_management_page.php?information=Błąd szyfrowania hasła ");
  }
}
if (empty($_POST['home_address'])) {
  $_POST['home_address']=$x['home_address'];
}
if (empty($_POST['user_type'])) {
  $_POST['user_type']=$x['user_type'];
}

$sql = "Update users set name='$_POST[name]', surname='$_POST[surname]', birthday = '$_POST[birthday]', password= '$hash', email_address = '$_POST[email_address]', home_address= '$_POST[home_address]', user_type='$_POST[user_type]' where user_id = $_POST[user_id];";

$con->query($sql);
$con->close();

header("location: ../pages/user_management_page.php?information=Podsumowanie edycji użytkownika: user_id(stałe):$_POST[user_id], imie: $x[name] -> $_POST[name], nazwisko: $x[surname] -> $_POST[surname], <br>data urodzin: $x[birthday] -> $_POST[birthday], adres email: $x[email_address] -> $_POST[email_address], hasło: zaszyfrowane -> $_POST[password], adres domowy: $x[home_address] -> $_POST[home_address], typ użytkownika: $x[user_type] -> $_POST[user_type]");


 ?>
