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
       $sql = "SELECT user_id, name, surname, birthday,password,email_address, home_address, user_type FROM `users`;";
       $res=$con->query($sql);


       if (!empty($_GET['information'])) {
         echo "<div><p>$_GET[information]</p><br>";
       }else {
         echo "<div>";
       }

       echo <<< tomek
       <table>
         <tr>
           <th>ID użytkownika</th>
           <th>Imię</th>
           <th>Nazwisko</th>
           <th>Data urodzin</th>
           <th>Haslo</th>
           <th>Adres email</th>
           <th>Adres domowy</th>
           <th>Uprawnienia użytkownika</th>
           <th colspan=2 ><a href=./user_adding_page.php>Dodaj użytkownika</a></th>
         </tr>

         <form id="user_adding_form" action="../actions/add_user.php" method="post"></form>
         <tr>
           <td>
              Id użytkownika zostanie <br> nadane automatycznie
             <input type="hidden" name="product_id" value="" form="user_adding_form">
           </td>
           <td>
             <input type="text" name="name" placeholder="podaj imię" form="user_adding_form">
           </td>
           <td>
             <input type="text" name="surname" placeholder="podaj nazwisko" form="user_adding_form">
           </td>
           <td>
             <input type="date" name="birthday" placeholder="podaj datę urodzin" form="user_adding_form">
           </td>
           <td>
             <input type="password" name="password" placeholder="podaj haslo " form="user_adding_form">
           </td>
           <td>
             <input type="email" name="email_address" placeholder="podaj adres mailowy" form="user_adding_form">
           </td>
           <td>
             <input type="text" name="home_address" placeholder="podaj adres domowy" form="user_adding_form">
           </td>
           <td>
             <select name="user_type" form="user_adding_form">
               <option value="admin">admin</option>
               <option value="manager">manager</option>
               <option value="client">client</option>
             </select>
           </td>

           <td>
             <input type="submit"  value="Zatwierdź" form="user_adding_form">
           </td>
           <td><a href='./user_management_page.php?information=anulowano dodawanie użytkownika'>Anuluj dodawanie</a></td>
       tomek;

       while ($x=$res->fetch_assoc()) {

         echo <<< tomek
           <tr>
           <td>$x[user_id]</td>
           <td>$x[name]</td>
           <td>$x[surname]</td>
           <td>$x[birthday]</td>
           <td class='password'>$x[password]</td>
           <td>$x[email_address]</td>
           <td>$x[home_address]</td>
           <td>$x[user_type]</td>

           <td><a href=../actions/delete_user.php?id=$x[user_id]>Usuń</a></td>
           <td><a href=./user_edition_page.php?id=$x[user_id]>Edytuj</a></td>

           </tr>
       tomek;
       }
       echo "</table></div>";

      $con->close();
      ?>
     </section>

   </body>
 </html>
