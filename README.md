# PHP-Captcha
Generate Captcha with normal image format via php

## Usage
Set option for **`SimpleCaptcha`**:
```php
$captcha_option = array(
    'captcha_code' => '',
    'captcha_image_height' => 60,
    'captcha_image_width' => 250,
    'captcha_letter' => 'bcdfghjkmnpqrstvwxyz23456789',
    'captcha_font' => dirname(__FILE__).'font/monofont.ttf',
    'captcha_text_color' => '0x142864',
    'captcha_noise_color' => '0x142864',
    'total_character' => 6,
    'random_captcha_dots' => 50,
    'random_captcha_lines' => 25,
    'check_sensitive' => false
);
```
The captcha code is compared case insensitively by default  
If you want case sensitive match, update the option `check_sensitive` from `false` to `true`

## Screenshot
![carry0987](https://i.imgur.com/o39C2Lg.png)
