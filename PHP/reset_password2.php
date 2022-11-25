<?php

session_start();

$passwordError = "";

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


// if ($_SERVER["REQUEST_METHOD"] == "GET") {

if (
  isset($_COOKIE["email"])
  && $_COOKIE["email"] == $_SESSION["user_email"]
) {
  $authorized = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {



  /* Validate Password Strength */
  $password = $_POST["password"];
  $confirmPassword = $_POST["confirmPassword"];

  if (!empty($password)) {
    if ($password != $confirmPassword) {
      $passwordError = "Password And Confirmed Password Doesn't Match";
    } else {
      if (
        !strlen($password) >= 8 // 8 characters minimum
        || !preg_match("/[@#$%^&*)({}\":|\';':\/?.><,`!~§±+=_-]/", $password) // One special character
        || !preg_match("/[A-Z]/", $password) // One uppercase character
        || !preg_match("/[a-z]/", $password) // One lowercase character
        || !preg_match("/[0-9]/", $password) // One number
      ) {
        $passwordError = "Your Password is Weak, Please Follow The Given Rules";
      }
    }

    if ($passwordError == "") {
      $query = "UPDATE users SET password = '" . $password . "' WHERE id='" . $_SESSION["user_id"] . "';";

      if ($connection->query($query) === TRUE) {
        $reset = true;
      } else {
        echo "Reseting Failed \n";
      }
    }
  } else {
    $passwordError = "Password Can't Be Empty";
  }
}
$connection->close();
?>


<!DOCTYPE html>
<html>

<head>
  <title>Reset Password</title>
  <link rel="stylesheet" type="text/css" href="../CSS/Shared.css">
  <link rel="stylesheet" type="text/css" href="../CSS/reset_password.css">
  <script src="../JavaScript/reset_password.js"></script>
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
        <td style="background-color:rgba(0, 0, 0, 0.2);width:15%"></td>
        <td colspan="5">
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1 class="style">Reset Your Password</h1>

            <?php
            if ($reset) {
              echo "<div class=\"desc\"><h3>Your Password Has Been Updated Successfully</h3></div>";
              $reset = null;
              session_destroy();
            }
            ?>

            <?php
            if ($authorized) {
            ?>

              <label> New Password: <br>
                <input id="password" type="password" name="password" size="30"> <span class="err"><?php echo $passwordError; ?></span>
              </label>
              <p id="passHelp" class="helpPara"></p>
              <br><br>

              <label> Confirm Password: <br>
                <input id="confirmPassword" type="password" name="confirmPassword" size="30">
              </label>
              <p id="confirmPassHelp" class="helpPara"></p>

              <br><br><br>

              <p>
                <input class="submit" type="submit" value="Submit" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
                <input class="clear" type="reset" value="Clear" onclick="return confirm('Are you sure you want to clear the form?');" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
              </p>

            <?php
            } else {
            ?>
              <div class="desc">
                <h3>You Are Unauthorized To Access This Page</h3>
              </div>
            <?php
            }
            ?>
          </form>
        </td>
        <td style="background-color:rgba(0, 0, 0, 0.2);width:15%"></td>
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