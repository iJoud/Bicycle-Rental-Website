<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Contact Us</title>
    <link rel="stylesheet" type="text/css" href="../CSS/Shared.css">
    <link rel="stylesheet" type="text/css" href="../CSS/register.css">
    <script src="../JavaScript/contact_us.js"></script>
    <style>
        .messageHelp{
            font-size: 10px;
        }
        form {
            padding-left: 600px;
        }
    </style>


</head>

<body>

    <?php
    // for sending email
    require '../phpmailer/PHPMailer.php';
    require '../phpmailer/SMTP.php';
    require '../phpmailer/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\STMP;
    use PHPMailer\PHPMailer\Exception;

    $nameError = $emailError = $phoneError = $msgError = "";
    $name = $email = $phone = $msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check name validity
        if (empty($_POST["fullName"])) {
            $nameError = "Please Enter Your Full Name";
        } else {
            $name = $_POST["fullName"];
            if (!preg_match("/^[a-zA-Z\s]*$/", $name)) {
                $nameError = "Invalid Name! Should Consist Only Of Letters and Spaces.";
            }

            // echo strlen($name);
            if (strlen($name) > 100) {
                if ($nameError != "") {
                    $nameError .= "<br> ";
                }
                $nameError .= "Your Name Should Not Exceed 100 Character Lenght";
            }
        }


        // Check email validity
        if (empty($_POST["email"])) {
            $emailError = "Please Enter Your Email";
        } else {
            $email = $_POST["email"];
            if (!preg_match("/^[0-9a-zA-Z_.]+@[0-9a-zA-Z_]+?\.[a-zA-Z]{2,3}$/", $email)) {
                $emailError = "Invalid Email Format!";
            }

            if (strlen($email) > 100) {
                if ($emailError != "") {
                    $emailError .= "<br> ";
                }
                $emailError .= "Your Email Should Not Exceed 100 Character Lenght";
            }
        }

        // Check phone number validity
        if (empty($_POST["phone"])) {
            $phoneError = "Please Enter Your Phone Number";
        } else {
            $phone = $_POST["phone"];
            if (!preg_match("/^05[0-9]{8}$/", $phone)) {
                $phoneError = "Invalid Phone Number!";
            }

            if (strlen($phone) > 10) {
                if ($phoneError != "") {
                    $phoneError .= "<br> ";
                }
                $phoneError .= "Your Phone Number Should Not Exceed 10 Character Lenght";
            }
        }


        // Check Message validity
        if (empty($_POST["msg"])) {
            $msgError = "Please Enter Your Message";
        } else {
            $msg = $_POST["msg"];
            if (!preg_match("/^[^*|\<>[\]#{}\\();&$]+$/", $msg)) {
                $msgError = "Invalid Message! Please Remove Any Special Characters";
            }
            if (strlen($msg) > 200) {
                if ($msgError != "") {
                    $msgError .= "<br> ";
                }
                $msgError .= "Your Message Should Not Exceed 200 Character Lenght";
            }
        }

        if (
            $nameError == "" && $emailError == "" && $phoneError == "" &&  $msgError == ""
            && $name != "" && $email != "" && $phone != "" && $msg != ""
        ) {

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

            $query = "INSERT INTO contacts (name, phone, email, message)
            VALUES ('" . $name . "', '" . $phone . "', '" . $email . "', '" . $msg . "');";

            if ($connection->query($query) === TRUE) {
                $last_id = $connection->insert_id;

                $_SESSION["contact_id"] = $last_id;
                $_SESSION["contact_completed"] = TRUE;

                // header("Location: contact_us.php"); /* Redirect */
                // exit();
            } else {
                echo "faild to store the form\n";
            }


            $connection->close();
        }
    }


    if ($_SESSION["contact_completed"] != null) {


        // start database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "BikeRentalWebsite";

        $connection = new mysqli($servername, $username, $password, $database);

        $query = "SELECT * FROM contacts WHERE id='" . $_SESSION["contact_id"] . "';";

        $currentContact = $connection->query($query);

        if ($currentContact->num_rows > 0) {

            $row = $currentContact->fetch_assoc();

            $name = $row["name"];
            $email = $row["email"];
            $phone = $row["phone"];
            $msg = $row["message"];

            // Send email to verify it "check if email exist, then we can send email to it!"
            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = "true";
            $mail->SMTPSecure = "tls";
            $mail->Port = "587";
            $mail->isHTML(true);
            $mail->Username = "cpit405project@gmail.com";
            $mail->Password = "rehxebtjugblugki";
            $mail->Subject = "We Recive Your Message!";
            $mail->setFrom("cpit405project@gmail.com");
            $mail->Body = "
            Hello $name,
            We receive the following message from you <br>
            $msg
            <br><br>
            Your Phone Number : $phone <br>
            Your Email Address : $email <br>
            <br>
            Our team received your information and will respond to you ASAP.
            <br><br>
            Best Regards, CPIT405 Project Team :)
            ";

            $mail->addAddress($email);
            if ($mail->Send()) {
                $_SESSION["email_sent"] = true;
            } else {
                $_SESSION["email_sent"] = false;
            }
            $mail->smtpClose();

            $_SESSION["contact_completed"] = null;

            $connection->close();
        }
    }



    ?>


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

                <td class="text" colspan="7">
                    <div class="title">
                        <br>
                        <h1>Contact Us</h1>
                        <h3 class="desc">If There Is Any Question, Fill Up The Form Below And We'll Reach You.</h3>
                        <?php
                        if ($_SESSION["email_sent"]) {
                            echo "<h2 class=\"success\">Thank You $name! We Receive Your Message And Sent You An Email.</h2>";
                            $name = $email = $phone = $msg = "";
                            $_SESSION["email_sent"] = null;
                        }
                        ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td  colspan="7">


                    <form style="margin-left:-40px"  method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >

                        <label> Full Name: <br>
                            <input id="fullName" type="text" name="fullName" size="40" placeholder="e.g. Will Smith" value="<?php if ($name != "") echo $name; ?>"> <span class="err"><?php echo $nameError; ?></span>
                        </label>
                        <p id="fullnameHelp" class="helpPara"></p>
                        <br><br>

                        <label> Email: <br>
                            <input id="email" type="text" name="email" size="40" placeholder="e.g. WillSmith@gmail.com" value="<?php if ($email != "") echo $email; ?>"> <span class="err"><?php echo $emailError; ?></span>
                        </label>
                        <p id="emailHelp" class="helpPara"></p>
                        <br><br>

                        <label> Phone Number: <br>
                            <input id="phone" type="text" name="phone" size="40" placeholder="e.g. 05XXXXXXXX" value="<?php if ($phone != "") echo $phone; ?>"> <span class="err"><?php echo $phoneError; ?></span>
                        </label>
                        <p id="phoneHelp" class="helpPara"></p>
                        <br><br>

                        <label> Message: <br>
                            <textarea id="msg" name="msg" rows="6" cols="40" placeholder="Write Your Message here."><?php if ($msg != "") echo $msg; ?></textarea> <span class="err"><?php echo $msgError; ?></span>
                        </label>
                        <p id="messageHelp" class="helpPara"></p>
                        <br><br>

                        <p>
                            <input class="submit" type="submit" value="Send" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
                            <input class="clear" type="reset" value="Clear" onclick="return confirm('Are you sure you want to clear the form?');" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'">
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