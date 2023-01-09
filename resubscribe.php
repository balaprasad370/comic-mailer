<?php
session_start();
require_once __DIR__ . "/databaseConnection.php";

$subscribeErr = "";
$emailId = "";
if (isset($_SERVER['REQUEST_METHOD'])) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['subscribe'])) {


            function sanitize_data($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            if (isset($_POST['email'])) {
                //Updating element
                $email = sanitize_data($_POST['email']);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $subscribeErr = "Email should be valid";
                    $emailid = $email;
                } else {
                    // Checking valid user

                    $check_query = "SELECT * FROM visitor_det where email=? and verified = 1;";
                    $stmt = mysqli_stmt_init($mysqli);

                    if (!mysqli_stmt_prepare($stmt, $check_query)) {
                        $subscribeErr =  "<h2 style='color:#ff0000;'>Something went Wrong</h2>";
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $email);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                        $query_no_rows = mysqli_stmt_num_rows($stmt);
                        mysqli_stmt_close($stmt);
                    }


                    if ($query_no_rows == 1) {
                        // sanitizing data SQL injection              


                        $update_data = "UPDATE visitor_det SET action='start' WHERE email=?;";
                        $stmt = mysqli_stmt_init($mysqli);

                        if (!mysqli_stmt_prepare($stmt, $update_data)) {
                            $subscribeErr =  "<h2 style='color:#ff0000;'>Something went Wrong</h2>";
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $email);
                            $update = mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }


                        if ($update) {
                            header('Location:index.php');
                        }
                    } else {
                        $subscribeErr = "<p style='color:#ff0000;'>Email does not exist.</p>";
                    }
                }
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

    <title>Rtcamp Asssignment</title>

    <link rel="stylesheet" href="public/style.css">

</head>

<body>
    <form action="" method="post">
        <div class="center-div">
            <div class="in-center-div">
                <h4 class="title-data">SUBSCRIBE XKCD Comics</h4>

                <strong>
                    <h4>
                        <label for="email">Email</label>
                    </h4>
                </strong>

                <input type="email" name="email" id="email" placeholder="Enter your email id" class="email-input" required>
                <span id="email-input-err"></span>

                <span style="padding:10px;">
                    <?php echo "<span id='load-err'> $subscribeErr </span>"; ?>
                </span>

                <p style="font-size: 14px; font-weight:100;color:black;">Please click <strong> subscribe</strong> to add your account for mailing.</p>

                <button type="submit" class="send-btn" style="margin-top: 40px;" name="subscribe">SUBSCRIBE</button>

                <br />
                <br />
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