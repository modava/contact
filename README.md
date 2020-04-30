Contact Backend
=============
Phiên bản contact dành cho modava

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist "modava/contact @dev"
```

or add

```
"modava/contact": "@dev"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

in file common/config/main.php add:
```php
'modules' => [
    'contact' => [
        'class' => 'modava\contact\Contact',
    ]
]
```

Run
-----

```php
http://localhost/project/contact
```