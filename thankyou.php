<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rtCamp Asssignment</title>
    <link rel="stylesheet" href="public/style.css">
</head>

<body>

    <div class="center-div">
        <div class="in-center-div">
            <h2 class="title-data">XKCD Comics</h2>
            <div style="padding: 20px 0;">

                <p><strong>Thank you..!</strong></p>

                <?php
                if (isset($_SESSION['email'])) {

                    echo "<p class='ptext'> " . $_SESSION['email'] . "</p>";
                }
                ?>

            </div>
            <p style="padding: 20px 0 50px 0;"><strong>Please check your email</strong> for further instructions on how to complete your account setup.</p>
            <br>
            <p>Once you are done with verification then you will receive Comics for every fiveminutes</p>

            <br />
            <br />
            <div class="footer-div">
                <a href="/">Home</a>
                <a href="/unsubscribe.php">Unsubscribe</a>
                <a href="/resubscribe.php">Resubscribe</a>

            </div>
        </div>
    </div>

</body>

</html>