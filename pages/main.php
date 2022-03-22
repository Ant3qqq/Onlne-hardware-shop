<!--
ogarnąć permisje userów na podstawie bazy danych
zamówienia - dla admina i managera


 -->

<?php
session_start();
 ?>
<!DOCTYPE html>

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
            $sql = "SELECT user_type FROM `users` where email_address like '$_SESSION[email_address_logged]';";
            $res=$con->query($sql);
            $con->close();
            $x=$res->fetch_assoc();
            if ($x['user_type'] == 'admin') {
              echo <<< tomek
                <a href="./products_management_page.php">Zarządzanie produktami</a>
                <a href="./user_management_page.php">Zarządzanie użytkownikami</a>
              tomek;
            }elseif ($x['user_type'] == 'manager') {
              echo <<< tomek
                <a href="./products_management_page.php">Zarządzanie produktami</a>
              tomek;
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

        if (isset($_GET['action'])  and $_GET['action']=='zarejestruj') {
          echo <<< tomek
          <div class='center'>Zarejestruj
          <form action="../actions/registration.php" method="post">
            <input type="text" name="name" placeholder="podaj imie"><br>
            <input type="text" name="surname" placeholder="podaj nazwisko"><br>
            <input type="date" name="birthday"><br>
            <input type="password" name="password" placeholder="podaj haslo"><br>
            <input type="email" name="email_address" placeholder="podaj email"><br>
            <input type="text" name="home_address" placeholder="podaj adres zamieszkania"><br>
            <input type="submit" value="zatwierdz">
          </form>
          </div>
          tomek;

        }elseif (isset($_GET['action'])  and $_GET['action']=='zaloguj') {
          echo <<< tomek
          <div class='center'>Zaloguj
          <form action="../actions/logon.php" method="post">
          <input type="email" name="email_address" placeholder="podaj email"><br>
            <input type="password" name="password" placeholder="podaj haslo"><br>
            <input type="submit" value="zatwierdz">
          </form>
          </div>
          tomek;
        }else {
          $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
          $sql = "SELECT product_id, name, amount, price, image_name, visibility FROM `products`;";
          $res=$con->query($sql);
          $con->close();
          echo "<div class=flex>";

          if (!empty($_SESSION['cart_content'])) {
            $cart_session=$_SESSION['cart_content'];
          }

          while ($x=$res->fetch_assoc()) {
            if (empty($x['image_name'])) {
              $x['image_name']='default.png';
            }

            if (isset($cart_session[$x['product_id']])) {
              $x['amount'] = $x['amount'] - $cart_session[$x['product_id']];
            }

            echo <<< tomek
            <div class="product">
                <form action="../actions/add_to_cart.php" method="get">
                  <img src="../product_images/$x[image_name]" alt="zdjecie produktu"><br>
                  <p>Nazwa produktu: $x[name]</p>
                  <p>Ilośc na magazynie: $x[amount] szt</p>
                  <p>Cena: $x[price] zł</p>
                  <input type="submit" value="Dodaj do koszyka">
                  <input type="hidden" name="product_id" value="$x[product_id]">
                  <input type="number" name="amount" value='1' min='1' step='1' max=$x[amount]>
                </form>
            </div>
          tomek;
          }

          echo "</div>";
        }
      ?>

    </section>
  <footer>Strona wykoana przez Antonieg Pietrzaka</footer>
  </body>
</html>
