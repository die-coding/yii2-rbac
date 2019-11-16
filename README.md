# Yii2 RBAC

RBAC / Auth Manager for Yii2 Extends `'mdmsoft/yii2-admin': '~2.0'`

[![Latest Stable Version](https://poser.pugx.org/diecoding/yii2-rbac/v/stable?format=flat-square)](https://github.com/die-coding/yii2-rbac/releases)
[![Total Downloads](https://poser.pugx.org/diecoding/yii2-rbac/downloads?format=flat-square)](https://packagist.org/packages/diecoding/yii2-rbac)
[![Daily Downloads](https://poser.pugx.org/diecoding/yii2-rbac/d/daily?format=flat-square)](https://packagist.org/packages/diecoding/yii2-rbac)
[![License](https://poser.pugx.org/diecoding/yii2-rbac/license?format=flat-square)](LICENSE.md)
[![Quality Score](https://img.shields.io/scrutinizer/g/die-coding/yii2-rbac.svg?style=flat-square)](https://scrutinizer-ci.com/g/die-coding/yii2-rbac)
[![Build Status](https://img.shields.io/scrutinizer/build/g/die-coding/yii2-rbac.svg?style=flat-square)](https://scrutinizer-ci.com/g/die-coding/yii2-rbac/build-status/master)

## Documentation

> **Disclaimer:** This pakage is actually only used to make it easier for me to build my personal application, but if you are interested please just try it. This package is extends from ['mdmsoft/yii2-admin': '~2.0'](https://github.com/mdmsoft/yii2-admin/), you can also use documentation from [https://github.com/mdmsoft/yii2-admin/](https://github.com/mdmsoft/yii2-admin/).

-   [Change Log](CHANGELOG.md).
-   [Installation](#installation).
-   [Authorization Guide](https://www.yiiframework.com/doc/guide/2.0/en/security-authorization). Important, read this first before you continue.
-   [Configuration](https://github.com/die-coding/yii2-rbac/blob/master/documentation/configuration.md)
-   [Using Menu](https://github.com/die-coding/yii2-rbac/blob/master/documentation/menu.md).
-   [Screenshots](https://github.com/die-coding/yii2-rbac/blob/master/documentation/screenshots).

## Installation

### Install With Composer

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```
composer require diecoding/yii2-rbac "dev-master"
```

Or, you may add

```
"diecoding/yii2-rbac": "dev-master"
```

to the require section of your `composer.json` file and execute `composer update`.

### Install From the Archive

Download the latest release from here [releases](https://github.com/die-coding/yii2-rbac/releases), then extract it to your project.
In your application config, add the path alias for this extension.

```php
return [
    ...
    'aliases' => [
        '@diecoding/rbac' => 'path/to/your/extracted',
        // for example
        // '@diecoding/rbac' => '@app/extensions/diecoding/yii2-rbac-1.0.0',
        ...
    ],
    ...
];
```
