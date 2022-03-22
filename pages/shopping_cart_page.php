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


          $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

          $cart_session=$_SESSION['cart_content'];

          echo "<div class='flex_column'>";
          $cart_price = 0;
          foreach ($cart_session as $key => $value) {
              $sql = "SELECT name, amount, price, image_name FROM `products` where product_id = $key;";
              $res=$con->query($sql);

              while ($x=$res->fetch_assoc()) {
                    if (empty($x['image_name'])) {
                      $x['image_name']='default.png';
                    }
                    $price_of_items = $x['price']*$value;

                    $cart_price=$cart_price+$price_of_items;

                    echo <<< tomek
                      <div class='product_in_cart'>
                        <img src="../product_images/$x[image_name]" alt="zdjecie produktu"><br><br>
                        <p>Nazwa produktu: $x[name]</p><br>
                        <p>Ilośc na magazynie: $x[amount]</p><br>
                        <p>Ilośc w koszyku:$value</p><br>
                        <p>Cena: $x[price]</p><br>
                        <p>Suma: $price_of_items zł</p>

                      </div><br>
                    tomek;
                }
          }
          echo <<< tomek
            <div class="product">
            podsumowanie zakupów: cena: $cart_price zł
            <a href="../actions/confirm_order.php">Przejdź do podsumowania</a>
            </div>
            </div>
          tomek;
      ?>

    </section>

  <footer>Strona wykoana przez Antonieg Pietrzaka</footer>
  </body>
</html>
