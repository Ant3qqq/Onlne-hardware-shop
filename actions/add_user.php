<?php
session_start();

if ( !empty($_POST['name']) &&  !empty($_POST['surname']) && !empty($_POST['birthday']) && !empty($_POST['password']) && !empty($_POST['email_address']) && !empty($_POST['home_address']) && !empty($_POST['user_type']) ) {

  $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
  $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
  if (password_verify($hash, $_POST['password'])) {
    header("location: ../pages/user_adding_page.php?information=Błąd szyfrowania hasła ");
  }else {
    $sql = "INSERT INTO `users` (`user_id`, `name`, `surname`, `birthday`, `password`, `email_address`, `home_address`,`user_type`) VALUES (NULL, '$_POST[name]', '$_POST[surname]', '$_POST[birthday]', '$hash', '$_POST[email_address]', '$_POST[home_address]', '$_POST[user_type]');";

    $con->query($sql);
    $con->close();
    header("location: ../pages/user_adding_page.php?information=Zarejestrowano usera '$_POST[name]'");

  }

}else {
  header('location: ../pages/user_adding_page.php?information=podaj wszystkie dane');
}

 ?>
