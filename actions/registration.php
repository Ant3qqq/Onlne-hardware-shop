<?php
session_start();

$uppercase = preg_match('@[A-Z]@', $_POST['password']);
$lowercase = preg_match('@[a-z]@', $_POST['password']);
$number    = preg_match('@[0-9]@', $_POST['password']);
$specialChars = preg_match('@[^\w]@', $_POST['password']);


if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8) {
  header('location: ../pages/main.php?action=zarejestruj&information=Hasło powinno mieć przynajmniej 8 liter, jedną wielką literę, jeden numer i jeden znak specjalny');

}else{
  if ( !empty($_POST['name']) &&  !empty($_POST['surname']) && !empty($_POST['birthday']) && !empty($_POST['password']) && !empty($_POST['email_address']) && !empty($_POST['home_address']) ) {
    $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

    $sql = "SELECT `name` FROM `users` WHERE email_address like '$_POST[email_address]'";
    $res=$con->query($sql);
    $x = $res->fetch_assoc();
    if (empty($x['name'])) {

      $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
      if (password_verify($hash, $_POST['password'])) {
        header("location: ../pages/main.php?information=Błąd szyfrowania hasła ");
      }else {
        $sql = "INSERT INTO `users` (`user_id`, `name`, `surname`, `birthday`, `password`, `email_address`, `home_address`) VALUES (NULL, '$_POST[name]', '$_POST[surname]', '$_POST[birthday]', '$hash', '$_POST[email_address]', '$_POST[home_address]');";

        $con->query($sql);
        $con->close();
        header("location: ../pages/main.php?information=Zarejestrowano usera '$_POST[name]'");

      }
    }else {
      header("location: ../pages/main.php?information=mail $_POST[email_address] jest już w użyciu&action=zarejestruj");
    }

  }else {
    header('location: ../pages/main.php?information=podaj wszystkie dane&action=zarejestruj');
  }
}
$con->cloes();
 ?>
