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
       $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
       $sql = "SELECT o.order_id, u.name as user_name, surname, u.user_id, date_time, products.name, op.amount, products.price, home_address ,status FROM `orders` o join ordered_products op on op.order_id = o.order_id join users u on u.user_id = o.user_id join products on op.product_id = products.product_id where u.user_id = '$_SESSION[user_id]' order by o.order_id";
       $res=$con->query($sql);

       if (!empty($_GET['information'])) {
         echo "<div><p>$_GET[information]</p><br>";
       }else {
         echo "<div>";
       }

       echo <<< tomek
       <table>
         <tr>
           <th>ID zamówienia</th>
           <th>Imię i nazwisko klienta</th>
           <th>ID klienta</th>
           <th>Data i czas zamówienia</th>
           <th>Towar</th>
           <th>Ilosć</th>
           <th>Cena</th>
           <th>W sumie za produkt</th>
           <th>Cena zamówienia</th>
           <th>Adres dostawy</th>
           <th>Status zamówienia</th>
         </tr>
       tomek;

       $old_id = 0;
       while ($x=$res->fetch_assoc()) {
         // ogarnianie statusu - zrobić
        $product_zusammen_price = $x['price']*$x['amount'];

        if ($old_id != $x['order_id']) {
          $sql = "SELECT count(order_id) as rowspan FROM  ordered_products where order_id = $x[order_id]";
          $in_while_res = $con->query($sql);
          $result_table = $in_while_res->fetch_assoc();

          $old_id = $x['order_id'];


            echo <<< tomek
            <tr>
            <td rowspan=$result_table[rowspan]>$x[order_id]</td>
            <td rowspan=$result_table[rowspan]>$x[user_name] $x[surname]</td>
            <td rowspan=$result_table[rowspan]>$x[user_id]</td>
            <td rowspan=$result_table[rowspan]>$x[date_time]</td>
            <td>$x[name]</td>
            <td>$x[amount]</td>
            <td>$x[price] zł</td>
            <td>$product_zusammen_price zł</td>
            <td rowspan=$result_table[rowspan]>cena w sumie</td>
            <td rowspan=$result_table[rowspan]>$x[home_address]</td>
            <td rowspan=$result_table[rowspan]>$x[status]</td>


            </tr>
            tomek;
        }else{
          echo <<< tomek
          <tr>

            <td>$x[name]</td>
            <td>$x[amount]</td>
            <td>$x[price] zł</td>
            <td>$product_zusammen_price zł</td>

          </tr>
          tomek;
        }
      }

         echo "</table></div>";

      $con->close();
      ?>
     </section>

   </body>
 </html>
