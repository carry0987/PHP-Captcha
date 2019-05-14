<?php
session_start();
require 'class/class_captcha.php';

$captcha_option = array(
    'captcha_code' => '',
    'captcha_image_height' => 60,
    'captcha_image_width' => 250,
    'captcha_letter' => 'bcdfghjkmnpqrstvwxyz23456789',
    'captcha_font' => 'font/monofont.ttf',
    'captcha_text_color' => '0x142864',
    'captcha_noise_color' => '0x142864',
    'total_character' => 6,
    'random_captcha_dots' => 50,
    'random_captcha_lines' => 25,
    'check_sensitive' => false
);

$captcha = new SimpleCaptcha();
$captcha->setCaptchaOption($captcha_option);

if (isset($_GET['rand'])) {
    echo $captcha->generateCaptcha();
    exit();
}

$status = '';
if (isset($_POST['captcha']) && $_POST['captcha'] !== '') {
    if ($captcha->checkCaptcha($_SESSION['captcha'], $_POST['captcha']) !== 0) {
        $status = '<span class="captcha_failed">Entered captcha code does not match! Please try again</span>';
    } else {
        $status = '<span class="captcha_success">Your captcha code is match</span>';    
    }
} else {
    $status = '';
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0 ,maximum-scale=1.0, initial-scale=1">
    <title>PHP Simple Captcha</title>
    <meta name="author" content="carry0987">
    <meta name="description" content="This program was made by carry0987">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link href="https://www.nehscsa.com/messageboard/favicon.ico" rel="shortcut icon" />
</head>

<body>
    <div id="mainwrapper">
        <div class="captcha_div">
            <h1>Create a Simple Captcha</h1>
            <p class="status"><?=$status;?></p>
            <form action="" id="form" class="captcha_form" method="post">
                <label>Enter Captcha :</label>
                <input type="text" class="captcha_input" name="captcha" id="captcha" placeholder="Captcha" autocomplete="off" />
                <div class="forgot_password">
                    <img src="index.php?rand=<?=rand();?>" id='captcha_image'>
                </div>
                <div>
                    <p>Can't read the image? <a href='javascript: refreshCaptcha();'>click here</a> to refresh</p>
                </div>
                <div class="captcha_submit">
                    <button type="submit" name="submit">Check</button>
                </div>
            </form>
        </div>
        <script type="text/javascript">
        function refreshCaptcha() {
            var img = document.getElementById('captcha_image');
            var img_attr = img.getAttribute('src');
            img.src = img_attr.substring(0, img_attr.lastIndexOf('?')) + '?rand=' + Math.random() * 1000;
        }
        </script>
    </div>
</body>

</html>
