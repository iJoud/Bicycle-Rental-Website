<?php
session_start();

// for sending email
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\STMP;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($fname, $email, $id)
{

  $mail = new PHPMailer();

  $mail->isSMTP();
  $mail->Host = "smtp.gmail.com";
  $mail->SMTPAuth = "true";
  $mail->SMTPSecure = "tls";
  $mail->Port = "587";
  $mail->isHTML(true);
  $mail->Username = "cpit405project@gmail.com";
  $mail->Password = "rehxebtjugblugki";
  $mail->Subject = "Account Verification Link";
  $mail->setFrom("cpit405project@gmail.com");

  $mail->Body = "
    Hello $fname,
    You Should Verify Your Account By Accessing <a href=\"http://localhost/Website/PHP/verification.php\">This Link<a> Within 10 Minutes To Verify Your Account.
    <br>
    <b>*NOTE: </b>The Link Will Expire After 10 Minutes
    <br><br>
    Best Regards, CPIT405 Project Team :)
    ";

  $mail->addAddress($email);
  $mail->Send();

  $mail->smtpClose();

  $_SESSION["user_email"] = $email;
  $_SESSION["user_id"] = $id;
  setcookie("email", $_SESSION["user_email"], time() + (60 * 10), "/");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $passError = $emailError = "";

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

  /* Validate Email Structure */
  if (isset($_POST["email"])) {
    if (!preg_match("/^[0-9a-zA-Z_.]+@[0-9a-zA-Z_]+?\.[a-zA-Z]{2,3}$/", $_POST["email"])) {
      $emailError = "Invalid Email Structure!";
    } else {


      $NotVerifiedError = false;
      $emaillPassError = false;


      $query = "SELECT id, fname, email, password, verified FROM users WHERE email='" . $_POST["email"] . "';";

      $userExist = $connection->query($query);

      if ($userExist->num_rows == 1) {

        $user = $userExist->fetch_assoc();

        if ($user["verified"] == "yes") {
          if ($_POST["password"] == $user["password"]) {

            $_SESSION["currentUserName"] = $user["fname"];
            $_SESSION["currentUserid"] = $user["id"];

            echo "<script>window.location='../index.php'</script>";

          } else {
            $passError = "Wrong Password";
          }
          
        } else {
          $NotVerifiedError = true;
          sendVerificationEmail($user["fname"], $user["email"], $user["id"]);
        }
      } elseif ($userExist->num_rows == 0) {
        echo "<script>alert('You Don\'t Have An Account, You Will Be Redirected To Registration Page.'); window.location='register.php'</script>";
      }
    }


  } else {
    $emailError = "Email Can't Be Empty";
  }
  $connection->close();
}



?>


<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
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
          <h1 class="pageTitle">Login</h1>

          <div class="ver" style="display: <?php echo $NotVerifiedError ? "block" : "none"; ?>;">
            <h2>Your Account Is Not Verified</h2>
            <h3>We Sent You an Email To Verify Your Account</h3>
          </div>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <label> E-mail: <br>
              <input id="email" type="text" name="email" size="30" placeholder="e.g. mail@example.com"><span class="err"><?php echo $emailError; ?></span>
            </label>
            <br><br>

            <label> Password: <br>
              <input id="password" type="password" name="password" size="30" placeholder="********"><span class="err"><?php echo $passError; ?></span>
            </label>

            <p class="forgot">Forgot password? <a href="reset_password1.php">Click here to reset.</a></p>
            <br>

            <p>
              <input class="submit" type="submit" value="Submit" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
              <input class="clear" type="reset" value="Clear" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" onclick="return confirm('Are you sure you want to clear the form?');">
            </p>
          </form>
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