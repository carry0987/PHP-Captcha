<?php
namespace carry0987\Captcha;

/**
 * Simple Captcha Generator
 */
class SimpleCaptcha
{
    private $option = array();

    public function __construct()
    {
        $this->option = array(
            'code' => '',
            'image_height' => 60,
            'image_width' => 250,
            'captcha_letter' => 'bcdfghjkmnpqrstvwxyz23456789',
            'font_file' => 'monofont.ttf',
            'text_color' => '#142864',
            'noise_color' => '#142864',
            'total_character' => 6,
            'random_dots' => 50,
            'random_lines' => 25,
            'check_sensitive' => false
        );
    }

    public function setCaptchaOption(array $option)
    {
        foreach ($option as $key => $value) {
            if (isset($this->option[$key])) {
                $this->option[$key] = $value;
            }
        }
        if (file_exists($this->option['font_file']) !== true) {
            exit('Could not find the ttf file !');
        }
    }

    public function checkCaptcha(string $captcha_code, string $submit_code)
    {
        if ($this->option['check_sensitive'] === true || $this->option['check_sensitive'] === 1) {
            $check_result = strcmp($captcha_code, $submit_code);
        } else {
            $check_result = strcasecmp($captcha_code, $submit_code);
        }

        return $check_result === 0;
    }

    private function hexToRGB(string $hex_string)
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
            $this->option['code'] .= substr($this->option['captcha_letter'], mt_rand(0, strlen($this->option['captcha_letter'])-1), 1);
            $count++;
        }
        $this->option['captcha_font_size'] = $this->option['image_height'] * 0.65;
        try {
            $captcha_image = imagecreate($this->option['image_width'], $this->option['image_height']);
        } catch (\Exception $e) {
            echo '<h1>'.$e->getMessage().'</h1>';
            exit();
        }
        //Config background,text and noise color
        $background_color = imagecolorallocate($captcha_image, 255, 255, 255);
        $array_text_color = $this->hexToRGB($this->option['text_color']);
        $this->option['text_color'] = imagecolorallocate($captcha_image, $array_text_color['red'], $array_text_color['green'], $array_text_color['blue']);
        $noise_color = $this->hexToRGB($this->option['noise_color']);
        $image_noise_color = imagecolorallocate($captcha_image, $noise_color['red'], $noise_color['green'], $noise_color['blue']);
        //Generate random dots in background of the captcha image
        for ($count = 0; $count < $this->option['random_dots']; $count++) {
            imagefilledellipse(
                $captcha_image, 
                mt_rand(0,$this->option['image_width']), 
                mt_rand(0,$this->option['image_height']), 
                2, 
                3, 
                $image_noise_color
            );
        }
        //Generate random lines in background of the captcha image
        for ($count = 0; $count < $this->option['random_lines']; $count++) {
            imageline(
                $captcha_image, 
                mt_rand(0, $this->option['image_width']), 
                mt_rand(0, $this->option['image_height']), 
                mt_rand(0, $this->option['image_width']), 
                mt_rand(0, $this->option['image_height']), 
                $image_noise_color
            );
        }
        //Create a text box and add 6 captcha letters code in it
        $text_box = imagettfbbox(
            $this->option['captcha_font_size'], 
            0, 
            $this->option['font_file'], 
            $this->option['code']
        );
        $x = round(($this->option['image_width'] - $text_box[4]) / 2);
        $y = round(($this->option['image_height'] - $text_box[5]) / 2);
        imagettftext(
            $captcha_image, 
            $this->option['captcha_font_size'], 
            0, $x, $y, 
            $this->option['text_color'], 
            $this->option['font_file'], 
            $this->option['code']
        );
        $captcha['captcha_image'] = $captcha_image;
        $captcha['code'] = $this->option['code'];

        return $captcha;
    }

    public function getCaptchaImage($image)
    {
        imagejpeg($image);
        //Destroying the image instance
        imagedestroy($image);

        return $image;
    }
}
