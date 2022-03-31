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
       $sql = "SELECT user_id, name, surname, birthday,password, email_address, home_address, user_type FROM `users`;";
       $res=$con->query($sql);

       if (!empty($_GET['information'])) {
         echo "<span class='title'>Zarządzanie użytkownikami</span><div><p>$_GET[information]</p><br>";
       }else {
         echo "<span class='title'>Zarządzanie użytkownikami</span><div>";
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
         if ($x['user_id']==$_GET['user_id']) {
           echo <<< tomek
             <form id="user_editing_form" action="../actions/edit_user.php" method="post"></form>
             <tr>
               <td>
                  Id użytkownika zostanie <br> nadane automatycznie
                 <input type="hidden" name="user_id" value="$x[user_id]" form="user_editing_form">
               </td>
               <td>
                 $x[name]<br>
                 <input type="text" name="name" placeholder="podaj imię" form="user_editing_form">
               </td>
               <td>
                 $x[surname]<br>
                 <input type="text" name="surname" placeholder="podaj nazwisko" form="user_editing_form">
               </td>
               <td>
                 $x[birthday]<br>
                 <input type="date" name="birthday" placeholder="podaj datę urodzin" form="user_editing_form">
               </td>
               <td class='password'>
                  $x[password]<br>
                 <input type="password" name="password" placeholder="podaj haslo " form="user_editing_form">
               </td>
               <td>
                 $x[email_address]<br>
                 <input type="email" name="email_address" placeholder="podaj adres mailowy" form="user_editing_form">
               </td>
               <td>
                 $x[home_address]<br>
                 <input type="text" name="home_address" placeholder="podaj adres domowy" form="user_editing_form">
               </td>
               <td>
                 $x[user_type]<br>
                 <select name="user_type" form="user_editing_form">
                   <option value="admin">admin</option>
                   <option value="manager">manager</option>
                   <option value="client">client</option>
                 </select>
               </td>

               <td>
                 <input type="submit"  value="Zapisz zmiany" form="user_editing_form">
               </td>
               <td><a href='./user_management_page.php?information=anulowano edycję użytkownika'>Anuluj edycję</a></td>
           tomek;
         }else{
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

           <td><a href=../actions/delete_user.php?user_id=$x[user_id]>Usuń</a></td>
           <td><a href=./user_edition_page.php?user_id=$x[user_id]>Edytuj</a></td>

           </tr>
           tomek;
         }
       }
       echo "</table></div>";

      $con->close();
      ?>
     </section>
<footer>Strona wykoana przez Antoniego Pietrzaka</footer>

   </body>
 </html>
