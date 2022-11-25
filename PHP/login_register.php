<!DOCTYPE html>
<html>
  <head>
    <title>Login/Register</title>
    <link rel="stylesheet" type="text/css" href="../CSS/Shared.css">
    <link rel="stylesheet" type="text/css" href="../CSS/login_register.css">
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
        <tr class="bg">
          <td colspan="2"><div><img src="../Images/user.png" alt="user logo" width=100px hight=500px><h2>Already have an account?</h2><br><a href="login.php">Login Here!</a></div></td>
          <td colspan="5"><div><img src="../Images/new user.png" alt="new user logo" width=100px hight=500px><h2>New user?</h2><br><a href="register.php">Register Now!</a></div></td>
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
