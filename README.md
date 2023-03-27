Yii2 TTS
========
Yii2 TTS

O'rnatish
------------

Ushbu kengaytmani o'rnatishning afzal usuli - [composer](http://getcomposer.org/download/) orqali.

O'rnatish uchun quyidagi buyruqni ishga tushiring:

```
php composer.phar require --prefer-dist uzdevid/yii2-tts "1.0.0"
```

Agar siz composer global o'rnatgan bo'lsangiz, quyidagi buyruqni ishga tushiring:

```
composer require --prefer-dist uzdevid/yii2-tts "1.0.0"
```

Yoki quyidagi qatorni `composer.json` faylga qo'shing:

```
"uzdevid/yii2-tts": "1.0.0"
```

Foydalanish
-----

config/web.php faylga quyidagi qatorlarni qo'shing:

```php
'components' => [
    'tts' => [
        'class' => \uzdevid\TTS\TTS::class,
        'token'=> 'avtorizatsiya tokeni',
        'project_id' => 'loyihangiz-identifikatori',
        'voice' => \uzdevid\TTS\TTSOptions::VOICE_MALE,
        'enableCache' => true,
        'cacheDuration' => 3600 * 24 * 30,
    ],
],
```

Matnni o'qitish uchun quyidagi kodni yozing:

```php
    $text = 'Assalomu alaykum!, bu Yii2 TTS kengaytmani ishlatish uchun misol';
    $file = Yii::$app->tts->synthesize($text);
```


