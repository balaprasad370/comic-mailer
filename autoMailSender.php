<?php
session_start();
require_once __DIR__ . "/databaseConnection.php";
//Create an instance; passing `true` enables exceptions
class autoMailSender
{
    public function SendMail($receiver, $imageUrl)
    {
        // Sending email
        $to = implode("", $receiver);
        // for debug
        // echo $to;
        // return;

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




        //$to      = $receiver;
        $subject = "Enjoy reading today's most interesting XKCD comics";
        $message = '
<html>
<head>
<title>Your email ' . $to . ' is listed in our XKCD comics subscribers.</title>
</head>
<body> 
<p>This is lovely XKCD comics picture.</p>
    <img src=' . $imageUrl . '>
</body>
</html>';
        // File.
        $content = chunk_split(base64_encode($imageFile));

        // A random hash will be necessary to send mixed content.
        $semiRand     = md5(time());
        $mimeBoundary = "==Multipart_Boundary_x{$semiRand}x";

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

        mail($to, $subject, $body, $headers);
    }
}

// set array
$array = array();

//create an instrance for class autosendmail
$automail = new autoMailSender();


$email_query = "SELECT email FROM visitor_det WHERE action='start';";
$stmt = mysqli_stmt_init($mysqli);

if (!mysqli_stmt_prepare($stmt, $email_query)) {
    echo "There was an error";
} else {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $array[] = $row;
    }
    mysqli_stmt_close($stmt);
}



// debug:
// print_r($array);
// exit();
// Array length
// print_r ("<br>".count($array));

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


if (count($array) > 0) {
    foreach ($array as $val) {
        $automail->SendMail($val, $imgUrl);
    }
}
