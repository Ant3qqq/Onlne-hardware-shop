<?php
      $email = $_POST["email_address"];
      $verification_code = $_POST["verification_code"];

      // connect with database
      $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

      $x  = date("Y-m-d H:i:s");
      // mark email as verified
      $sql = "UPDATE users SET email_verified_at = '$x' WHERE email_address = '$email' AND verification_code = '$verification_code'";
      $result = $con->query($sql);

      echo "$email";
      echo "$verification_code";
      if (mysqli_affected_rows($con) == 0)
      {
          header('location: ../pages/main.php?information=weryfikacja nie udana &action=zaloguj');
      }

      header('location: ../pages/main.php?information=teraz możesz się zalogować&action=zaloguj');


 ?>
