<?php
session_start();
require_once 'databaseConnection.php';
$vkeyErr = "";
function SendMail($receiver, $imageUrl)
{
    $imageFile = file_get_contents($imageUrl);

    $tokens = explode('/', $imageUrl);

    // File name.
    $fileName = $tokens[(count($tokens) - 1)];

    // File extension.
    $ext = explode(".", $fileName);

    // File type.
    $fileType = $ext[1];

    // File size.
    $header = get_headers($imageUrl, true);

    $fileSize = $header['Content-Length'];




    $to      = $receiver;
    $subject = "Enjoy reading today's most interesting XKCD comics";
    $message = '
<html>
<head>
<title>Your email ' . $to . ' is listed in our XKCD comics subscribers.</title>
</head>
<body> 
    <img src=' . $imageUrl . '>
</body>
</html>';
    // File.
    $content = chunk_split(base64_encode($imageFile));

    // A random hash will be necessary to send mixed content.
    $semiRand     = md5(time());
    $mimeBoundary = '==Multipart_Boundary_x{$semiRand}x';

    // Carriage return type (RFC).
    $eol = "\r\n";

    $headers  = 'MIME-Version: 1.0' . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$mimeBoundary}\"" . $eol;
    $headers .= 'Content-Transfer-Encoding: 7bit' . $eol;
    $headers .= 'X-Priority: 3' . $eol;
    $headers .= 'X-Mailer: PHP' . phpversion() . $eol;

    // Message.
    $body  = '--' . $mimeBoundary . $eol;
    $body .= "Content-Type: text/html; charset=\"UTF-8\"" . $eol;
    $body .= 'Content-Transfer-Encoding: 7bit' . $eol;
    $body .= $message . $eol;

    // Attachment.
    $body .= '--' . $mimeBoundary . $eol;
    $body .= "Content-Type:{$fileType}; name=\"{$fileName}\"" . $eol;
    $body .= 'Content-Transfer-Encoding: base64' . $eol;
    $body .= "Content-disposition: attachment; filename=\"{$fileName}\"" . $eol;
    $body .= 'X-Attachment-Id: ' . rand(1000, 99999) . $eol;
    $body .= $content . $eol;
    $body .= '--' . $mimeBoundary . '--';

    $success = mail($to, $subject, $body, $headers);
}

// Fetching the random image.
$no = rand(1, 614);
$value = curl_init("https://xkcd.com/" . $no . "/info.0.json");
curl_setopt($value, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($value, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($value);
curl_close($value);
$data = json_decode($result);
// echo $data->img;
if ($data != '') {

    $imgUrl = $data->img;
} else {
    $imgUrl  = '';
}


if (isset($_GET['vkey']) && isset($_GET['email'])) {

    // sanitizing data SQL injection  
    function sanitize_data($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = sanitize_data($_GET['email']);
    $vkey  = sanitize_data($_GET['vkey']);

    $check_query = "SELECT vkey,verified,action FROM `visitor_det` WHERE email =? and vkey=?";
    $stmt = mysqli_stmt_init($mysqli);

    if (!mysqli_stmt_prepare($stmt, $check_query)) {
        $vkeyErr =  "<h2 style='color:#ff0000;'>Something went Wrong</h2>";
    } else {
        mysqli_stmt_bind_param($stmt, "ss", $email, $vkey);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $verified_status = $row['verified'];
            $action_status = $row['action'];
        }
        mysqli_stmt_close($stmt);
    }


    $count_stmt = mysqli_stmt_init($mysqli);
    if (!mysqli_stmt_prepare($count_stmt, $check_query)) {
        $vkeyErr =  "<h2 style='color:#ff0000;'>Something went Wrong</h2>";
    } else {
        mysqli_stmt_bind_param($count_stmt, "ss", $email, $vkey);
        mysqli_stmt_execute($count_stmt);
        mysqli_stmt_store_result($count_stmt);
        $query_no_rows = mysqli_stmt_num_rows($count_stmt);
        mysqli_stmt_close($count_stmt);
    }
    if ($query_no_rows == 1) {
        // updating data values.
        if ($verified_status == 0) {
            $action_status = "start";
            SendMail($email, $imgUrl);
        }
        $update_data = "UPDATE visitor_det SET verified=1,action='$action_status' WHERE vkey=?;";
        $stmt = mysqli_stmt_init($mysqli);

        if (!mysqli_stmt_prepare($stmt, $update_data)) {
            $vkeyErr =  "<h2 style='color:#ff0000;'>Something went Wrong</h2>";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $vkey);
            $update = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        if ($update) {

            $vkeyErr = "<h2 style='color:green;'>You are verified</h2>";
        }
    } else {
        $vkeyErr = "<h2 style='color:#ff0000;'>Invalid link</h2>";
    }
} else {
    $vkeyErr = "<h2 style='color:#ff0000;'>Invalid link</h2>";
}



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
            <center>

                <h2 class="title-data">XKCD Comics</h2>

                <h3 style="padding:10px 0;"><strong>Thank you..!</strong></h3>

                <?php
                if (isset($_SESSION['email'])) {

                    echo "<strong><p style='padding:10px;'>" . $email .  "</p></strong>";
                }
                ?>
                <br>
                <br>

                <span class="help-block">
                    <?php echo "$vkeyErr"; ?>
                </span>
                <br><br>

                <div class="footer-div">
                    <a href="/">Home</a>
                    <a href="/unsubscribe.php">Unsubscribe</a>
                    <a href="/resubscribe.php">Resubscribe</a>

                </div>
            </center>
        </div>
    </div>


</body>

</html>