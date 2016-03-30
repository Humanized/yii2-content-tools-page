# Yii2-Content Tools Page - README
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

Provides a simple CMS framework for Yii2 using the content-tools text editor.

## Features

## Installation

### Install Using Composer

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ composer require humanized/yii2-content-tools-page "dev-master"
```

or add

```
"humanized/yii2-content-tools": "dev-master"
```

to the ```require``` section of your `composer.json` file.


### Add Module to Configuration

Add following lines to the configuration file:

```php
'modules' => [
    'cms' => [
        'class' => 'humanized\contenttools\Module',
    ],
],
```

For full instructions how to configure this module, check the [CONFIG](CONFIG.md)-file.

### Run Migrations 

```bash
$ php yii migrate/up --migrationPath=@vendor/humanized/yii2-user/migrations
```

For full instructions on how to use this module, once configured, check the [USAGE](USAGE.md)-file.
