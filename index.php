<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Homepage</title>
  <link rel="stylesheet" type="text/css" href="CSS/Shared.css">
  <link rel="stylesheet" type="text/css" href="CSS/index.css">
</head>

<body>
  <table>
    <!--Header/navigation bar-->
    <thead>
      <tr>
        <th><img src="Images/bicycle.png" alt="bicycle logo" width=90px></th>
        <th style="text-align: left; width: 350px;">Bicycle Rental</th>
        <th><a href="index.php">Home</a></th>
        <th><a href="PHP/rental.php">Rental</a></th>
        <th><a href="PHP/contact_us.php">Contact</a></th>
        <th><a href="PHP/about_us.php">About Us</a></th>
        <?php
        if ($_SESSION["currentUserName"] != null) {
          echo "<th style=\"text-align: center; width: 250px;\" >Hello " . $_SESSION["currentUserName"] . ", <a class=\"logout\"href=\"PHP/logout.php\">Sign out</a></th>";
        } else {
          echo "<th><a href=\"PHP/login_register.php\">Login/Register</a></th>";
        }
        ?>
      </tr>
    </thead>

    <tbody>
      <tr class="banner">
        <td class="bannerText" colspan="7">
          <h1>Rent a Bike Today!</h1>
        </td>
      </tr>
      <tr>
        <td class="text" colspan="7">
          <ul>
            <li>
              <h1><img src="Images/lightning.png" alt="storm logo" width=25px>Instant Booking!</h1>
              <p>Get instant confirmation of your bike and save your time and effort.</p>
            </li>

            <li>
              <h1><img src="Images/custom.png" alt="Customize logo" width=25px>Customize Your Ride!</h1>
              <p>Because of the diversity of our bikes, every customer can get the bike they want.</p>
            </li>

            <li>
              <h1><img src="Images/security.png" alt="safe and secure logo" width=25px>Safe & Secure!</h1>
              <p>Easy secure online checkout.</p>
            </li>

            <li>
              <h1><img src="Images/badge.png" alt="quality logo" width=25px>High Quality!</h1>
              <p>A very safe process to rent bikes due to the quality of bikes we offer and the bike stores we work with.</p>
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
              <p>Follow us on Twitter  <a href="https://twitter.com/CPIT405_Project"><img src="Images/twitter.png" class="TwitterImg"></a></p>
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