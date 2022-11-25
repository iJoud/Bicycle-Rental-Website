<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    // start database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "BikeRentalWebsite";

    $connection = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($connection->connect_errno) {
        echo "Failed to connect to the database " . $connection->connect_error;
        exit();
    }
    if (
        isset($_COOKIE["email"])
        && $_COOKIE["email"] == $_SESSION["user_email"]
    ) {
        $query = "UPDATE users SET verified = 'yes' WHERE id='" . $_SESSION["user_id"] . "';";

        if ($connection->query($query) === TRUE) {
            $verified = true;
        } else {
            echo "Verification Failed \n";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Verification</title>
    <link rel="stylesheet" type="text/css" href="../CSS/Shared.css">
    <link rel="stylesheet" type="text/css" href="../CSS/login.css">
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
                <td colspan="7">

                    <?php
                    if ($verified) {
                    ?>
                        <div class="title">
                            <h2>Your Account Is Successfully Verified</h2>
                        </div>
                    <?php
                        session_destroy();
                    } else {
                    ?>
                        <div class="title">
                            <h2>You Are Unauthorized To Access This Page</h2>
                        </div>
                    <?php
                    }
                    ?>

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