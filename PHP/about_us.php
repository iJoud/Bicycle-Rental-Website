<?php
session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>About Us</title>
    <link rel="stylesheet" type="text/css" href="../CSS/Shared.css">
    <link rel="stylesheet" type="text/css" href="../CSS/index.css">
  </head>
  <body>
    <table>
      <!--Header/navigation bar-->
      <thead>
        <tr>
        <th ><img src="../Images/bicycle.png" alt="bicycle logo" width=90px></th>
          <th style="text-align: left; width: 350px;">Bicycle Rental</th>
          <th><a href="../index.php">Home</a></th>
          <th><a href="rental.php">Rental</a></th>
          <th><a href="contact_us.php">Contact</a></th>
          <th><a href="about_us.php">About Us</a></th>
          <?php
          if ($_SESSION["currentUserName"] != null) {
            echo "<th style=\"text-align: center; width: 250px;\" >Hello " . $_SESSION["currentUserName"] . ", <a class=\"logout\"href=\"logout.php\">Sign out</a></th>";
          } else {
            echo "<th><a href=\"login_register.php\">Login/Register</a></th>";
          }
          ?>
          </tr>
      </thead>

      <tbody>
        <tr>
          <td class="text" colspan="7">
            <ul>
              <li>
              <h1>CPIT405 - Web Application Project Team</h1>
              <p>Jodi Almohyawi <br>
              Lara Zughlul <br>
              Joud Alghamdi</p>
              </li>

            </ul>
          </td>
        </tr>

      </tbody>

      <!--Footer bar-->
      <tfoot class="footerBg">
        <tr>
          <td colspan="2">
            <h3>Bicycle Rental</h3>
            <p>Our website provides a fast an easy way to rent a bike.<p>
              <p>Follow us on Twitter  <a href="https://twitter.com/CPIT405_Project"><img src="../Images/twitter.png" class="TwitterImg"></a></p>
          </td>
          <td colspan="5">
            <ul><h3>Emails</h3>
              <li><a class="emails" href="mailto:lzughlul@stu.kau.edu.sa">lzughlul@stu.kau.edu.sa</li>
              <li><a class="emails" href="mailto:jalghamdi0094@stu.kau.edu.sa">jalghamdi0094@stu.kau.edu.sa</li>
              <li><a class="emails" href="mailto:jalmohaywi@stu.kau.edu.sa">jalmohaywi@stu.kau.edu.sa </li>
            </ul>
          </td>
        </tr>
      </tfoot>
    </table>
  </body>
</html>
