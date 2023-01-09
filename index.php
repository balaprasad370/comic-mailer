<?php
session_start();
require_once __DIR__ . '/databaseConnection.php';
$email = "";
$emailErr  = NULL;
$emailid = "";
if (isset($_SERVER['REQUEST_METHOD'])) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        function SendMail($email, $vkey)
        {
            $to = $email;
            $subject = "XKCD Comics";
            $message = "Dear ,<br> We are excited to have you here. Please verify your account by clicking below button.<br><br><br><br>
			<button style='padding:14px 16px;background-color:#000fff;color:#fff;border:none;font-weight:800;outline:none;border-radius:5px;'><a style='color:#fff;text-decoration:none;' href='http://localhost/verify.php?email=$to&vkey=$vkey'>VERIFY</a></button><br> 
		<br><br>
		<strong>OR</strong><br>
		Paste this link in browser<br>
		<a href='http://localhost/verify.php?email=$to&vkey=$vkey'> http://localhost/verify.php?email=$to&vkey=$vkey</a><br>";
            $sender = "From: butrabalaprasad370@gmail.com\r\n";
            $sender .= "MIME-Version: 1.0" . "\r\n";
            $sender .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            if (mail($to, $subject, $message, $sender)) {
                return true;
            } else {
                return false;
            }
        }



        function sanitize_data($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if (isset($_POST['sendCode'])) {
            if (isset($_POST['email'])) {
                $email = sanitize_data($_POST['email']);



                if (strlen($email) < 4) {
                    $emailErr = "Email should be valid";
                    $emailid = $email;
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Email should be valid";
                    $emailid = $email;
                } else {
                    // sanitizing data SQL injection

                    $customized_necesary_sql = "SELECT * FROM visitor_det WHERE email=?;";
                    $stmt = mysqli_stmt_init($mysqli);

                    if (!mysqli_stmt_prepare($stmt, $customized_necesary_sql)) {
                        echo "There was an error";
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $email);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $count_status = $row['email'];
                        }
                        mysqli_stmt_close($stmt);
                    }

                    $action = "stop";
                    $vkey = bin2hex(random_bytes(40));
                    //Checking already user or not



                    if (empty($count_status)) {


                        $insert_data = "INSERT INTO visitor_det(email,vkey,action)VALUES(?,?,?);";
                        $stmt = mysqli_stmt_init($mysqli);

                        if (!mysqli_stmt_prepare($stmt, $insert_data)) {
                            echo "There was an error";
                        } else {
                            mysqli_stmt_bind_param($stmt, "sss", $email, $vkey, $action);
                            $insert = mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }
                        if ($insert) {
                            $_SESSION['email'] = $email;

                            if (SendMail($email, $vkey)) {

                                header("location:thankyou.php");
                            } else {

                                $emailErr = "Mail not sent.";
                            }
                        } else {

                            $emailErr = "Mail not sent.";
                        }
                    } else {
                        $emailErr = "You are already register";
                    }
                }
            } else {
                $emailErr = "Email is required";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XKCD Comics</title>
    <link rel="stylesheet" href="public/style.css">
</head>

<body>
    <form action="index.php" method="post">
        <div class="center-div">
            <div class="in-center-div">


                <h1 class="title-data">XKCD Comics</h1>
                <h2 style="font-weight: 600;">Subscribe for comics</h2>
                <div style="text-align: start;padding:15px 0 10px 0">


                    <div class="input-cls">
                        <strong>
                            <h4>
                                <label for="email">Email</label>
                            </h4>
                        </strong>

                        <input type="email" id="email" class="email-input" name="email" placeholder="Enter your email id" required>
                        <span id="email-input-err"></span>

                    </div>

                    <span>
                        <?php echo "<p style='color:#ff0000;' >$emailErr</p>"; ?>
                    </span>

                </div>

                <div style="padding: 2px 0;">
                    <button class="send-btn" type="submit" name="sendCode">Send Verification Code</button>
                </div>

                <br>
                <div class="footer-div">
                    <a href="/">Home</a>
                    <a href="/unsubscribe.php">Unsubscribe</a>
                    <a href="/resubscribe.php">Resubscribe</a>

                </div>

            </div>
        </div>
    </form>
    <script src="public/main.js"></script>
</body>

</html>