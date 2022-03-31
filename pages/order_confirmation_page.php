<?php
session_start();
 ?>
<!DOCTYPE html>
<!--
    pozamykać connecitons
    ogarnąć robienie zamówienia
      wciskanie zamówienia do bazy
      wyświetlanie zamówienia

 -->

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Sklep metalowy</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body>

    <header>
      <h1>Sklep metalowy Simba & somsiad</h1>
    </header>

    <nav>
        <?php
          if (!empty($_SESSION['email_address_logged'])) {
            echo <<< tomek
              <p>Zalogowano jako $_SESSION[name_logged] $_SESSION[surname_logged]</p>
              <a href="./main.php">Sklep</a>
              <a href="./shopping_cart_page.php">Koszyk</a>
              <a href=../actions/logout.php>Wyloguj</a>
            tomek;

            $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
            $sql = "SELECT utp.* FROM users u join user_types_permissions utp on utp.user_type = u.user_type where email_address like '$_SESSION[email_address_logged]';";
            $res=$con->query($sql);
            $x=$res->fetch_assoc();
            // print_r($x);

            if ($x['editing_users']) {
              echo "<a href='./user_management_page.php'>Zarządzanie użytkownikami</a>";
            }
            if ($x['editing_products']) {
              echo "<a href='./products_management_page.php'>Zarządzanie produktami</a>";
            }
            if ($x['managing_orders']) {
              echo "<a href='./order_management_page.php'>Zarządzanie zamówieniami</a>";
            }
            if ($x['displaying_orders']) {
              echo "<a href='./order_display_page.php'>Moje zamówienia</a>";
            }

          }else {
            echo <<< tomek
              <p>Przegladasz stronę jako gość - zaloguj aby korzystać korzystać ze wszystkich funkcji</p>
              <a href="./main.php">Sklep</a>
              <a href="./shopping_cart_page.php">Koszyk</a>
              <a href="./main.php?action=zarejestruj">Zarejestruj</a>
              <a href=./main.php?action=zaloguj>Zaloguj</a>
            tomek;
          }
         ?>
    </nav>

    <section>
      <?php
        if (!empty($_GET['information'])) {
          echo "<br>$_GET[information]<br>";
        }

      ?>

    </section>
  <footer>Strona wykoana przez Antoniego Pietrzaka</footer>
  </body>
</html>
