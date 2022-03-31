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
       $sql = "SELECT product_id, name, amount, price, image_name,visibility FROM `products`;";
       $res=$con->query($sql);

       if (!empty($_GET['information'])) {
         echo "<div><p>$_GET[information]</p><br>";
       }else {
         echo "<div>";
       }

       echo <<< tomek
       <table>
         <tr>
           <th>ID produktu</th>
           <th>Nazwa</th>
           <th>Ilość</th>
           <th>Cena</th>
           <th>Zdjęcie</th>
           <th>Widoczność <br>w sklepie</th>
           <th colspan=2 ><a href=./product_adding_page.php>Dodaj produkt</a></th>
         </tr>
       tomek;

       while ($x=$res->fetch_assoc()) {
         if ($x['visibility']==0) {
           $visibility='Ukryte';
         }else {
           $visibility='Widoczne';
         }
         echo <<< tomek
           <tr>
           <td>$x[product_id]</td>
           <td>$x[name]</td>
           <td>$x[amount]</td>
           <td>$x[price]</td>
           <td><img src="../product_images/$x[image_name]" alt="zdjecie produktu"></td>
           <td>$visibility</td>


           <td><a href=../actions/delete_product.php?id=$x[product_id]>Usuń</a></td>
           <td><a href=./product_edition_page.php?id=$x[product_id]>Edytuj</a></td>

           </tr>
       tomek;
       }
       echo "</table></div>";

      $con->close();
      ?>
     </section>
<footer>Strona wykoana przez Antoniego Pietrzaka</footer>

   </body>
 </html>
