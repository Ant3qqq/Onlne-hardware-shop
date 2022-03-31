<?php
session_start();

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';


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
      // początek wysyłania maila

          $name = $_POST["name"];
          $email = $_POST["email_address"];
          $password = $_POST["password"];

          //Instantiation and passing `true` enables exceptions
          $mail = new PHPMailer(true);

          try {

              //Send using SMTP
              $mail->isSMTP();

              //Set the SMTP server to send through
              $mail->Host = 'smtp.gmail.com';

              //Enable SMTP authentication
              $mail->SMTPAuth = true;

              //SMTP username
              $mail->Username = 'nowakmaras69@gmail.com';

              //SMTP password
              $mail->Password = 'Tadek@123';

              //Enable TLS encryption;
              $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

              //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
              $mail->Port = 587;

              //Recipients
              $mail->setFrom('nowakmaras69@gmail.com', 'Online hardware shop');

              //Add a recipient
              $mail->addAddress($email, $name);

              //Set email format to HTML
              $mail->isHTML(true);

              $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

              $mail->Subject = 'Email verification';
              $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

              $mail->send();
              // echo 'Message has been sent';

              $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);


              // insert in users table
              // $sql = "INSERT INTO users(name, email, password, verification_code, email_verified_at) VALUES ('" . $name . "', '" . $email . "', '" . $encrypted_password . "', '" . $verification_code . "', NULL)";
              // mysqli_query($conn, $sql);

              if (password_verify($hash, $_POST['password'])) {
                header("location: ../pages/main.php?information=Błąd szyfrowania hasła ");
              }else {
                // $sql = "INSERT INTO `users` (`user_id`, `name`, `surname`, `birthday`, `password`, `email_address`, `home_address`) VALUES (NULL, '$_POST[name]', '$_POST[surname]', '$_POST[birthday]', '$hash', '$_POST[email_address]', '$_POST[home_address]');";

                $sql = "INSERT INTO `users` (`user_id`, `name`, `surname`, `birthday`, `password`, `email_address`, `home_address`, `user_type`, `verification_code`, `email_verified_at`) VALUES (NULL, '$_POST[name]', '$_POST[surname]', '$_POST[birthday]', '$hash', '$_POST[email_address]', '$_POST[home_address]', 'client','$verification_code', null);";


                $con->query($sql);
                $con->close();
                header("location: ../pages/main.php?information=Zarejestrowano usera '$_POST[name]'");

              }

              header("Location: ../pages/email_verification_page.php?email=" . $email);
              exit();
          } catch (Exception $e) {
              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }

      // koniec wysyłania mai
    }else {
      header("location: ../pages/main.php?information=mail $_POST[email_address] jest już w użyciu&action=zarejestruj");
    }

  }else {
    header('location: ../pages/main.php?information=podaj wszystkie dane&action=zarejestruj');
  }
}

 ?>
