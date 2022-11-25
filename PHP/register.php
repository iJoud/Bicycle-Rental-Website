<?php

session_start();

// for sending email
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\STMP;
use PHPMailer\PHPMailer\Exception;


function connectDB()
{
  // start database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "BikeRentalWebsite";

  $connection = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($connection->connect_errno) {
    echo "Failed to connect to MySQL: " . $connection->connect_error;
    exit();
  }

  return $connection;
}

$fname = $lname = $email = $password = $confirmPassword = $disabilities =  "";
$emailError = $passwordError = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  /*  
If the user already has an account, then a log in page will be opened for the user 
Check if email exist in database
*/
  $connection = connectDB();
  $email = $_POST["email"];
  $query = "SELECT email FROM users WHERE email='" . $email . "';";

  $userExist = $connection->query($query);

  if ($userExist->num_rows == 1) {
    echo "<script>alert('You Already Has An Account, You Will Be Redirected To Login Page.'); window.location='login.php'</script>";
  } else {

    /*
   If the user has no account, then you should validate the user’s email structure and the password strength as follows:
        o 8 characters minimum
        o One special character
        o One uppercase character
        o One lowercase character
        o One number
  */

    /* (1) Validate Email Structure */
    if (!empty($email)) {

      if (!preg_match("/^[0-9a-zA-Z_.]+@[0-9a-zA-Z_]+?\.[a-zA-Z]{2,3}$/", $email)) {
        $emailError = "Invalid Email Structure!";
      }
    } else {
      $emailError = "Email Can't Be Empty";
    }

    /* (2) Validate Password Strength */
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
    } else {
      $passwordError = "Password Can't Be Empty";
    }

    /* If no error detected */
    if ($emailError == "" && $passwordError == "") {

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

      $_SESSION["user_email"] = $email;
      $_SESSION["user_password"] = $password;


      $fname = isset($_POST["fname"]) ? $_POST["fname"] : "EMPTY";
      $lname = isset($_POST["lname"]) ? $_POST["lname"] : "EMPTY";
      $gender = isset($_POST["gender"]) ? $_POST["gender"] : "EMPTY";
      $disabilities = isset($_POST["disabilities"]) ? $_POST["disabilities"] : "EMPTY";
      $deccription = isset($_POST["deccription"]) ? $_POST["deccription"] : "EMPTY";
      $Accessories = isset($_POST["Accessories"]) ? $_POST["Accessories"] : "EMPTY";


      $query = "INSERT INTO users (fname, lname, gender, email, password, haveDisabilities, disabilities, preferableAccessories, verified )  
     VALUES ('" . $fname . "','" . $lname . "','" . $gender . "','" . $email . "','" . $password . "','" . $disabilities . "','" . $deccription . "','" . $Accessories . "', 'no');";


      // add user to database
      if ($connection->query($query) === TRUE) {
        $user_id = $connection->insert_id;
        $_SESSION["user_id"] = $user_id;
      } else {
        echo "users not inserted\n";
      }

      if ($mail->Send()) {
        $_SESSION["email_sent"] = true;
      } else {
        $_SESSION["email_sent"] = false;
      }

      $mail->smtpClose();

      setcookie("email", $_SESSION["user_email"], time() + (60 * 10), "/");
    }
  }
  $connection->close();
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Register</title>
  <link rel="stylesheet" type="text/css" href="../CSS/Shared.css">
  <link rel="stylesheet" type="text/css" href="../CSS/register.css">
  <script src="../JavaScript/register.js"></script>
</head>

<body>

  <table>
    <!--Header/navigation bar-->
    <thead>
      <tr>
        <th><img src="../Images/bicycle.png" alt="bicycle logo" width=90px></th>
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
          <br>
          <h1 class="success"> Registr </h1>
          <br>

          <?php
          if ($_SESSION["email_sent"]) {
            echo "<br><br><h2 class=\"success\">We Have Sent You An Email To Verify Your Account Whithin 10 Minutes </h2><br>";
            $fname = $lname = $email = $password = $confirmPassword = $disabilities =  "";
            $_SESSION["email_sent"] = null;
          }
          ?>

          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm('Are you sure you want to submit the form?');">

            <label> First Name: <br>
              <input id="fname" type="text" name="fname" size="30" placeholder="e.g. Will" value="<?php echo $fname; ?>">
            </label>
            <p id="fnameHelp" class="helpPara"></p>
            <br><br>



            <label> Last Name: <br>
              <input id="lname" type="text" name="lname" size="30" placeholder="e.g. Smith" value="<?php echo $lname; ?>">
            </label>
            <p id="lnameHelp" class="helpPara"></p>
            <br>

            <!-- Dropdown menu -->
            <p>
              <label> Gender: <br>
                <select name="gender">
                  <option disabled selected value> -- Select -- </option>
                  <option>Male</option>
                  <option>Female</option>
                  <option>Prefer Not to Say</option>
                </select>
              </label>
            </p>

            <label> E-mail: <br>
              <input id="email" type="text" name="email" size="30" placeholder="e.g. mail@example.com" value="<?php echo $email; ?>"><span class="err"><?php echo $emailError; ?></span>
            </label>
            <p id="emailHelp" class="helpPara"></p>
            <br><br>

            <label> Password: <br>
              <input id="password" type="password" name="password" size="30"> <span class="err"><?php echo $passwordError; ?></span>
            </label>
            <p id="passHelp" class="helpPara"></p>
            <br><br>

            <label> Confirm Password: <br>
              <input id="confirmPassword" type="password" name="confirmPassword" size="30">
            </label>
            <p id="confirmPassHelp" class="helpPara"></p>
            <br>

            <!-- Radio buttons -->
            <p>
              Have any disabilities?<br>
              <label>
                <input name="disabilities" type="radio" value="Yes"> Yes &ensp;
              </label>
              <label>
                <input name="disabilities" type="radio" value="No" checked> No
              </label>
            </p>

            <!-- Textarea -->
            <label> If Any, please mention it: <br>
              <textarea id="deccription" name="comments" rows="3" cols="37" placeholder="Describe it here."><?php echo $disabilities; ?></textarea>
            </label>
            <p id="descHelp" class="helpPara"></p>
            <br>

            <!-- Checkboxes -->
            <p>
              Preferable accessories with bikes:<br>
              <label>
                <input name="Accessories" type="checkbox" value="Helmet" checked> Helmet &ensp;
              </label>
              <label>
                <input name="Accessories" type="checkbox" value="Gloves"> Gloves &ensp;
              </label>
              <label>
                <input name="Accessories" type="checkbox" value="bikeLock"> Bike Lock &ensp;
              </label>
            </p>


            <p>
              <input class="submit" type="submit" value="Submit" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
              <input class="clear" type="reset" value="Clear" onclick="return confirm('Are you sure you want to clear the form?');" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
            </p>


            <br>
            <p class="availableLocations" id="availableLocations" style="margin-bottom:10px; text-decoration: underline;">
            * Double Click to See Available Bikes Locations *
          </p>
          <p id="displayLocations" style="display: none; color:midnightblue;">
              - Ash Shati District, Bicycle Time Store <br>
              - Al Zahra District, Jeddah Cyclist Store
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
          <p>Our website provides a fast an easy way to rent a bike.
          <p>
          <p>Follow us on Twitter <a href="https://twitter.com/CPIT405_Project"><img src="../Images/twitter.png" class="TwitterImg"></a></p>
        </td>
        <td colspan="5">
          <ul>
            <h3>Emails</h3>
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