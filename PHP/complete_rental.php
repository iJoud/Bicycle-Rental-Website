<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "BikeRentalWebsite";

$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_errno) {
    echo "Failed to connect to the database: " . $connection->connect_error;
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Complete Rental</title>
    <link rel="stylesheet" type="text/css" href="../CSS/Shared.css">
    <link rel="stylesheet" type="text/css" href="../CSS/rental.css">

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

                <div class="pageTitle">
            <h1 class="pageTitle"> Bikes For Renting</h1>
            <h3 class="pageTitleDesc">Your Renting Order Has Been Completed Successfully</h3>
          </div>

                    <br>

                    <?php
                    $query = "SELECT DISTINCT bikeModel, type, price, age, location, rDate, rTime FROM bikes
                                WHERE id='" . $_SESSION["bike_id"] . "';";

                    $allBikes = $connection->query($query);


                    $row = $allBikes->fetch_assoc();

                    echo "<div class=\"bike\">
                            <img src=\"../Images/Bikes/" . $row["bikeModel"] . ".jpeg\" alt=\"bike\" class=\"bikeImg\">";

                    echo " <h2 class=\"bikeModel\">" . $row["bikeModel"] . "</h2>
                                <p class=\"detailsLine\">
                                <span class=\"smallTitles\">Type: </span> " . $row["type"] . " &nbsp; &nbsp; &nbsp; &nbsp;
                                <span class=\"smallTitles\">Age: </span> " . $row["age"] . "
                                </p>
                                <p class=\"detailsLine\">
                                <span class=\"smallTitles\">Location: </span> " . $row["location"] . " &nbsp; &nbsp; &nbsp; &nbsp;
                                <span class=\"smallTitles\">Price/Hour: </span> " . $row["price"] . "
                                </p>";

                    echo "<p class=\"detailsLine\" style=\"margin-top: -12px;\">";
                    echo "<span class=\"smallTitles\">Date: </span>" . $row["rDate"];

                    // Spaces
                    echo  "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp";

                    echo "<span class=\"smallTitles\">Time: </span>" . $row["rTime"];

                    echo "</p>";

                    echo "</div><br><br>";
                    ?>


                    <br>
                    <br>

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

<?php
$connection->close();
?>