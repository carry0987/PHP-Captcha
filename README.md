# PHP-Captcha
[![Latest Stable Version](https://img.shields.io/packagist/v/carry0987/captcha.svg?style=flat-square)](https://packagist.org/packages/carry0987/captcha)  
Generate Captcha with normal image format via php

## Installation
```bash
composer require carry0987/captcha
```

## Usage
Set option for **`SimpleCaptcha`**:
```php
$captcha_option = array(
    'code' => '',
    'image_height' => 60,
    'image_width' => 250,
    'captcha_letter' => 'bcdfghjkmnpqrstvwxyz23456789',
    'font_file' => dirname(__DIR__).'/font/monofont.ttf',
    'text_color' => '#142864',
    'noise_color' => '#142864',
    'total_character' => 6,
    'random_dots' => 50,
    'random_lines' => 25,
    'check_sensitive' => false
);
```
The captcha code is compared case insensitively by default  
If you want case sensitive match, update the option `check_sensitive` from `false` to `true`

## Screenshot
![carry0987](https://i.imgur.com/o39C2Lg.png)
