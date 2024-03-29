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
            $sql = "SELECT utp.* FROM users u join user_types_permissions utp on utp.user_type = u.user_type where email_address like '$_SESSION[email_address_logged]';";
            $res=$con->query($sql);
            $x=$res->fetch_assoc();
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
        echo "<div class='information'>$_GET[information]</div>";
      }


        if (empty($_SESSION["email_address_logged"])) {
         echo "<div class='information'>Zalguj się aby móc skorzystać z koszyka</div>";
        }elseif(empty($_SESSION["cart_content"])){
          echo "<div class='information'>Pusyt koszyk -&nbsp <a href='./main.php'> przejdź do sklepu </a>&nbsp i dodaj produkty</div>";
        }
        else{

          $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');

          $_SESSION['cart_content'];

          echo "<div class='flex_column'>";
          $cart_price = 0;
          foreach ($_SESSION['cart_content'] as $key => $value) {
              $sql = "SELECT name, amount, price, image_name FROM `products` where product_id = $key;";
              $res=$con->query($sql);

              while ($x=$res->fetch_assoc()) {
                    if (empty($x['image_name'])) {
                      $x['image_name']='default.png';
                    }
                    $price_of_items = $x['price']*$value;

                    $cart_price=$cart_price+$price_of_items;
                    $amount_in_magazine = $x['amount']-$value;
                    $max_amount = $x['amount']-$value;
                    $min_amount = -$value;
                    echo <<< tomek
                      <div class='product_in_cart'>
                        <div class="img">
                          <img src="../product_images/$x[image_name]" alt="zdjecie produktu">
                        </div>

                        <div class="product_in_cart_description">
                          <span>Nazwa produktu: $x[name]</span>
                          <span>Ilośc na magazynie: $amount_in_magazine</span>
                          <span>Cena: $x[price]</span>
                          <span>Ilośc w koszyku: $value</span>
                          <span>Suma: $price_of_items zł</span>
                        </div>

                        <div class="product_in_cart_actions">
                          <div>
                            <form action="../actions/add_to_cart.php" method="get">
                              <input type="hidden" name="src" value="shopping_cart_page">
                              <input type="hidden" name="product_id" value="$key">
                              <input type="submit" value="Zmień ilość w koszyku"><br><br>
                              <input type="number" name="amount" value='1' min='$min_amount' step='1' max='$max_amount'>
                            </form>
                          </div>
                        </div>


                      </div><br>
                    tomek;
                }
          }
          echo <<< tomek
            </div>
            <div class="summary">
              <h2>Podsumowanie zakupów</h2>
              <span>
                cena za całość: $cart_price zł
              </span>
              <a href="../actions/confirm_order.php">Potwierdzam zamówienie</a>
            </div>

          tomek;

        }
      ?>
    </section>
  <footer>Strona wykoana przez Antonieg Pietrzaka</footer>
  </body>
</html>
