<?php
session_start();

// for sending email
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\STMP;
use PHPMailer\PHPMailer\Exception;

function sendResetEmail($fname, $email, $id)
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
    $mail->Subject = "Reset Account Password Link";
    $mail->setFrom("cpit405project@gmail.com");

    $mail->Body = "
    Hello $fname,
    You Can Reset Your Account Password Via <a href=\"http://localhost/Website/PHP/reset_password2.php\">This Link<a> Within 10 Minutes.
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

    $errMsg = "";
    $sent = false;

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


    $query = "SELECT id, fname, email FROM users WHERE email='" . $_POST["email"] . "';";

    $userExist = $connection->query($query);

    if ($userExist->num_rows == 1) {

        $user = $userExist->fetch_assoc();

        $_SESSION["user_email"] = $user["email"];
        $_SESSION["user_id"] = $user["id"];

        sendResetEmail($user["fname"], $user["email"], $user["id"]);

        $sent = true;

    } elseif ($userExist->num_rows == 0) {
        $errMsg = "Email Doesn't Exist";
    }

    $connection->close();
}

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
                        <div class="desc">
                            <h2>Enter Your Email To Recive Password Reseting Link</h2>
                            <h3 style="display:<?php echo $sent ? "block" : "none"; ?>;">The Link Has Been Sent To Your Email, It'll Expire Within 10 Minutes</h3>
                        </div>
                        <label> Email: <br>
                            <input id="email" type="text" name="email" size="30"><span class="err"><?php echo $errMsg; ?></span>
                        </label>
                        <p id="passHelp" class="helpPara"></p>
                        <br><br>

                        <p>
                            <input class="submit" type="submit" value="Submit" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
                            <input class="clear" type="reset" value="Clear" onclick="return confirm('Are you sure you want to clear the form?');" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
                        </p>
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