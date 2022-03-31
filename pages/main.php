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

          echo "<div class=shop>";

          // paginacja początek
          // find out how many rows are in the table
          $sql = "SELECT COUNT(*) FROM products";
          $result = $con->query($sql);
          $r = $result->fetch_row();
          $numrows = $r[0];

          // number of rows to show per page
          $rowsperpage = 10;
          // find out total pages
          $totalpages = ceil($numrows / $rowsperpage);

          // get the current page or set a default
          if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
             // cast var as int
             $currentpage = (int) $_GET['currentpage'];
          } else {
             // default page num
             $currentpage = 1;
          } // end if

          // if current page is greater than total pages...
          if ($currentpage > $totalpages) {
             // set current page to last page
             $currentpage = $totalpages;
          } // end if
          // if current page is less than first page...
          if ($currentpage < 1) {
             // set current page to first page
             $currentpage = 1;
          } // end if

          // the offset of the list, based on current page
          $offset = ($currentpage - 1) * $rowsperpage;

          // get the info from the db
          $sql = "SELECT product_id, name, amount, price, image_name, visibility FROM `products` where visibility=1 LIMIT $offset, $rowsperpage";
          $result =  $con->query($sql);
          // while there are rows to be fetched...
          while ($x = $result->fetch_assoc()) {
            // echo data
            if (isset($_SESSION['cart_content'][$x['product_id']])) {
               $x['amount'] = $x['amount'] - $_SESSION['cart_content'][$x['product_id']];
            }
               if ($x['amount']>0 and $x['visibility']==1) {
                   echo <<< tomek
                   <div class="product">
                     <div class="img">
                       <img src="../product_images/$x[image_name]" alt="zdjecie produktu">
                     </div>
                     <div class="product_description">
                       <span>Nazwa produktu: $x[name]</span><br>
                       <span>Ilośc na magazynie: $x[amount] szt</span><br>
                       <span>Cena: $x[price] zł</span>
                     </div>
                     <div class="product_actions">
                       <form action="../actions/add_to_cart.php" method="get">
                       <input type="submit" value="Dodaj do koszyka"><br><br>
                       <input type="hidden" name="product_id" value="$x[product_id]">
                       <input type="hidden" name="src" value="main">
                       <input type="number" name="amount" value='1' min='1' step='1' max=$x[amount]> szt
                       </form>
                     </div>
                   </div>
                 tomek;
                 }

          } // end while
          /******  build the pagination links ******/
          // range of num links to show
          $range = 3;
          echo "<div class='pagination'>";
          // if not on page 1, don't show back links
          if ($currentpage > 1) {
             // show << link to go back to page 1
             echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
             // get previous page num
             $prevpage = $currentpage - 1;
             // show < link to go back to 1 page
             echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
          } // end if

          // loop to show links to range of pages around current page
          for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
             // if it's a valid page number...
             if (($x > 0) && ($x <= $totalpages)) {
                // if we're on current page...
                if ($x == $currentpage) {
                   // 'highlight' it but don't make a link
                   echo " [<b>$x</b>] ";
                // if not current page...
                } else {
                   // make it a link
                   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
                } // end else
             } // end if
          } // end for

          // if not on last page, show forward and last page links
          if ($currentpage != $totalpages) {
             // get next page
             $nextpage = $currentpage + 1;
              // echo forward link for next page
             echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
             // echo forward link for lastpage
             echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
          } // end if
          /****** end build pagination links ******/

          echo "</div>";
          // paginacja koniec
          echo "</div>";


        }
      ?>

    </section>
  <footer>Strona wykoana przez Antoniego Pietrzaka</footer>
  </body>
</html>
