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
       $con = new mysqli('localhost', 'root','','online_shop_anotni_pietrzak');
       $sql = "SELECT id, name, surname, birthday,password, email_address, home_address, user_type FROM `users`;";
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
           <th>Hasło</th>
           <th>Adres email</th>
           <th>Adres domowy</th>
           <th>Uprawnienia użytkownika</th>
           <th colspan=2 ><a href=./user_adding_page.php>Dodaj użytkownika</a></th>
         </tr>
       tomek;

       while ($x=$res->fetch_assoc()) {

         echo <<< tomek
           <tr>
           <td>$x[id]</td>
           <td>$x[name]</td>
           <td>$x[surname]</td>
           <td>$x[birthday]</td>
           <td class='password'>$x[password]</td>
           <td>$x[email_address]</td>
           <td>$x[home_address]</td>
           <td>$x[user_type]</td>

           <td><a href=../actions/delete_user.php?id=$x[id]>Usuń</a></td>
           <td><a href=./user_edition_page.php?id=$x[id]>Edytuj</a></td>

           </tr>
       tomek;
       }
       echo "</table></div>";

      $con->close();
      ?>
     </section>


   </body>
 </html>
