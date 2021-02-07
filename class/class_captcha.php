<?php
/**
 * Simple Captcha Generator
 */
class SimpleCaptcha
{
    private $option = array();

    public function __construct()
    {
    }

    public function setCaptchaOption($option)
    {
        $this->option = $option;
        if (file_exists($this->option['captcha_font']) !== true) {
            $error = 'Could not find the ttf file !';
            return $error;
        }
    }

    public function checkCaptcha($captcha_code, $submit_code)
    {
        if ($this->option['check_sensitive'] === true || $this->option['check_sensitive'] === 1) {
            $check_result = strcmp($captcha_code, $submit_code);
        } else {
            $check_result = strcasecmp($captcha_code, $submit_code);
        }
        return $check_result;
    }

    private function hexToRGB($hex_string)
    {
        if (preg_match('/#([a-f0-9]{3}){1,2}\b/i', $hex_string) === true) {
            $hex_string = trim($hex_string, '#');
        } else {
            $hex_string = 142864;
        }
        $integar = hexdec($hex_string);
        $hexresult = array('red' => 0xFF & ($integar >> 0x10), 'green' => 0xFF & ($integar >> 0x8), 'blue' => 0xFF & $integar);
        return $hexresult;
    }

    public function generateCaptcha()
    {
        //Count character
        $count = 0;
        while ($count < $this->option['total_character']) {
            $this->option['captcha_code'] .= substr($this->option['captcha_letter'], mt_rand(0, strlen($this->option['captcha_letter'])-1), 1);
            $count++;
        }
        $this->option['captcha_font_size'] = $this->option['captcha_image_height'] * 0.65;
        try {
            $captcha_image = imagecreate($this->option['captcha_image_width'], $this->option['captcha_image_height']);
        } catch (Exception $e) {
            echo '<h1>'.$e->getMessage().'</h1>';
            exit();
        }
        //Config background,text and noise color
        $background_color = imagecolorallocate($captcha_image, 255, 255, 255);
        $array_text_color = $this->hexToRGB($this->option['captcha_text_color']);
        $this->option['captcha_text_color'] = imagecolorallocate($captcha_image, $array_text_color['red'], $array_text_color['green'], $array_text_color['blue']);
        $noise_color = $this->hexToRGB($this->option['captcha_noise_color']);
        $image_noise_color = imagecolorallocate($captcha_image, $noise_color['red'], $noise_color['green'], $noise_color['blue']);
        //Generate random dots in background of the captcha image
        for ($count = 0; $count < $this->option['random_captcha_dots']; $count++) {
            imagefilledellipse(
                $captcha_image, 
                mt_rand(0,$this->option['captcha_image_width']), 
                mt_rand(0,$this->option['captcha_image_height']), 
                2, 
                3, 
                $image_noise_color
            );
        }
        //Generate random lines in background of the captcha image
        for ($count = 0; $count < $this->option['random_captcha_lines']; $count++) {
            imageline(
                $captcha_image, 
                mt_rand(0, $this->option['captcha_image_width']), 
                mt_rand(0, $this->option['captcha_image_height']), 
                mt_rand(0, $this->option['captcha_image_width']), 
                mt_rand(0, $this->option['captcha_image_height']), 
                $image_noise_color
            );
        }
        //Create a text box and add 6 captcha letters code in it
        $text_box = imagettfbbox(
            $this->option['captcha_font_size'], 
            0, 
            $this->option['captcha_font'], 
            $this->option['captcha_code']
        );
        $x = ($this->option['captcha_image_width'] - $text_box[4]) / 2;
        $y = ($this->option['captcha_image_height'] - $text_box[5]) / 2;
        imagettftext(
            $captcha_image, 
            $this->option['captcha_font_size'], 
            0, 
            $x, 
            $y, 
            $this->option['captcha_text_color'], 
            $this->option['captcha_font'], 
            $this->option['captcha_code']
        );
        //Show captcha image in the html page
        //Defining the image type to be shown in browser window
        header('Content-Type: image/jpg');
        //Showing the image
        imagejpeg($captcha_image);
        //Destroying the image instance
        imagedestroy($captcha_image);
        $_SESSION['captcha'] = $this->option['captcha_code'];
    }
}
