<?php
session_start();

if ( !empty($_POST['password']) &&  !empty($_POST['email_address']) ) {

  $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

  $sql = "SELECT password, email_verified_at from users WHERE email_address like '$_POST[email_address]';";
  $password_hash = $con->query($sql);
  $res = $password_hash->fetch_assoc();

  if (password_verify($_POST['password'], $res['password']) and $res['email_verified_at'] != 0) {
    $sql="SELECT user_id, name, surname, email_address FROM users where email_address like '$_POST[email_address]'";

    $res = $con->query($sql);
    $row = $res->fetch_assoc();

    $_SESSION['name_logged'] = $row['name'];
    $_SESSION['surname_logged'] = $row['surname'];
    $_SESSION['email_address_logged'] = $row['email_address'];
    $_SESSION['user_id'] = $row['user_id'];

    header("location: ../pages/main.php?information=zalogowano usera $row[name] $row[surname] o loginie $row[email_address]");
  }else {
    if ($email_verified_at == 0) {
        header('location: ../pages/main.php?information=konto nie zostało aktywowane - sprawdź skryznkę email&action=zaloguj');
    }else {
      header('location: ../pages/main.php?information=zle haso lub email usera, jeżeli nie masz jeszcze konta - zarejestruj się&action=zaloguj');
    }
  }

  $con->close();

}else {
  header('location: ../pages/main.php?information=podaj dane&action=zaloguj');
}

?>
