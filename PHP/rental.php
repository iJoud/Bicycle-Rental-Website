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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['submit'])) {

    if ($_SESSION["currentUserid"] != null) {
      echo "1";

      // retrive selected bike details
      $bikeName = $_POST['bikeName'];
      $time = $_POST['time'];
      $date = $_POST['date'];


      $query = "SELECT id FROM bikes WHERE bikeModel='" . $bikeName . "' AND rDate='" . $date . "' AND rTime LIKE '" . $time . "%';";

      $bikerow = $connection->query($query);

      $bike = $bikerow->fetch_assoc();
      $_SESSION["bike_id"] = $bike["id"];

      $query = "INSERT INTO rentalBikes (uid, bid) VALUES ('" . $_SESSION["currentUserid"] . "', '" . $bike["id"] . "');";
      
      if ($connection->query($query) === TRUE) {
      } else {
        echo "rental failed \n";
      }
      
      echo "<script>window.location='complete_rental.php'</script>";


    } else {
      $noUser = true;
    }
  }

}

?>


<!DOCTYPE html>
<html>

<head>
  <title>Rental</title>
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
            <h3 class="pageTitleDesc">Choose the sutible bike for you from the following bikes</h3>
          </div>

          <?php
          if ($noUser) {
            echo $_SESSION["currentUserid"];
          ?>
            <div class="userErr">
              <br>
              <h3> You Should Login To Rent A Bike</h3>
            </div>
            <br>
          <?php
          }
          ?>

          <?php
          $query = "SELECT DISTINCT bikeModel, type, price, age, location FROM bikes
            WHERE id NOT IN (SELECT bid FROM rentalBikes);";

          $allBikes = $connection->query($query);

          if ($allBikes->num_rows > 0) {

            while ($row = $allBikes->fetch_assoc()) {

              echo " <form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method=\"post\">
              
              <div class=\"bike\">
               <img src=\"../Images/Bikes/" . $row["bikeModel"] . ".jpeg\" alt=\"bike\" class=\"bikeImg\">";

              //  add hidden input element, to identify selected bike using $_POST array
              echo "
              <input name=\"bikeName\" value=" . $row["bikeModel"] . " hidden>
              <h2 class=\"bikeModel\">" . $row["bikeModel"] . "</h2>
              
              <p class=\"detailsLine\">
               <span class=\"smallTitles\">Type: </span> " . $row["type"] . " &nbsp; &nbsp; &nbsp; &nbsp;
               <span class=\"smallTitles\">Age: </span> " . $row["age"] . "
              </p>
              
              <p class=\"detailsLine\">
               <span class=\"smallTitles\">Location: </span> " . $row["location"] . " &nbsp; &nbsp; &nbsp; &nbsp;
               <span class=\"smallTitles\">Price/Hour: </span> " . $row["price"] . "
              </p>";

              $query = "SELECT DISTINCT rDate FROM bikes
              WHERE id NOT IN (SELECT bid FROM rentalBikes) AND  bikeModel='" . $row["bikeModel"] . "';";

              $AllDate = $connection->query($query);

              if ($AllDate->num_rows > 0) {
                echo "<p class=\"detailsLine\" style=\"margin-top: -12px;\">";
                echo "<span class=\"smallTitles\">Date: </span>";
                echo "<select class=\"dropdown\" name=\"date\" required>";
                echo "<option value=\"\" disabled selected>Select Date</option>";

                while ($date = $AllDate->fetch_assoc()) {
                  echo "<option value=" . $date["rDate"] . ">" . $date["rDate"] . "</option>";
                }
                echo "</select>";

                // Spaces
                echo  "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp";

                $query = "SELECT DISTINCT rTime FROM bikes
                WHERE id NOT IN (SELECT bid FROM rentalBikes) AND  bikeModel='" . $row["bikeModel"] . "';";

                $AllTime = $connection->query($query);

                if ($AllTime->num_rows > 0) {

                  echo "<span class=\"smallTitles\">Time: </span>
              <select class=\"dropdown\" name=\"time\" required>
                <option value=\"\" disabled selected>Select Time</option>";

                  while ($time = $AllTime->fetch_assoc()) {
                    echo "<option value=" . $time["rTime"] . ">" . $time["rTime"] . "</option>";
                  }
                  echo "</select>";
                }

                echo "</p>";
                echo "<input class=\"rentBike\" name=\"submit\" type=\"submit\" value=\"Rent\" onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\">";


                echo "</div>
                </form>
                 <br><br>";
              }
            }
          }

          $connection->close();
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