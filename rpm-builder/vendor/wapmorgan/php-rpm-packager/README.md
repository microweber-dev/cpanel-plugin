RPM packager (PHP)
==================
[![Composer package](http://xn--e1adiijbgl.xn--p1acf/badge/wapmorgan/php-rpm-packager)](https://packagist.org/packages/wapmorgan/php-rpm-packager) [![Build Status](https://travis-ci.org/wapmorgan/php-rpm-packager.svg)](https://travis-ci.org/wapmorgan/php-rpm-packager) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wapmorgan/php-rpm-packager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wapmorgan/php-rpm-packager/?branch=master)

A simple rpm packager for PHP applications.

Get composer:

```
curl -sS http://getcomposer.org/installer | php
```

Install dependencies and autoloader

```
php composer.phar install
```

Use it:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$spec = new \wapmorgan\rpm\Spec();
$spec
    ->setPackageName("my-package-name")
    ->setVersion("0.1.1")
    ->setDescription("My software description")
    ->setSummary('simple summary')
    ->setRelease('1')
    ->setUrl('http://...');
;

$packager = new \wapmorgan\rpm\Packager();

$packager->setOutputPath("/path/to/out");
$packager->setSpec($spec);

$packager->mount("/path/to/source-conf", "/etc/my-sw");
$packager->mount("/path/to/exec", "/usr/bin/my-sw");
$packager->mount("/path/to/docs", "/usr/share/docs");

//Creates folders using mount points
$packager->run();

//Get the rpmbuild command
echo $packager->build();
```

**Create the Package**

```
$(php pack.php)
```
