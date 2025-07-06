# Get In Packages' composer.json

[![Build Status](https://travis-ci.org/WyriHaximus/php-get-in-packages-composer.jason.svg?branch=master)](https://travis-ci.org/WyriHaximus/php-get-in-packages-composer.jason)
[![Latest Stable Version](https://poser.pugx.org/wyrihaximus/get-in-packages-composer.jason/v/stable.png)](https://packagist.org/packages/wyrihaximus/get-in-packages-composer.jason)
[![Total Downloads](https://poser.pugx.org/wyrihaximus/get-in-packages-composer.jason/downloads.png)](https://packagist.org/packages/wyrihaximus/get-in-packages-composer.jason/stats)
[![Code Coverage](https://scrutinizer-ci.com/g/WyriHaximus/php-get-in-packages-composer.jason/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/WyriHaximus/php-get-in-packages-composer.jason/?branch=master)
[![License](https://poser.pugx.org/wyrihaximus/get-in-packages-composer.jason/license.png)](https://packagist.org/packages/wyrihaximus/get-in-packages-composer.jason)
[![PHP 7 ready](http://php7ready.timesplinter.ch/WyriHaximus/php-get-in-packages-composer.jason/badge.svg)](https://appveyor-ci.org/WyriHaximus/php-get-in-packages-composer.jason)

# Installation

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/get-in-packages-composer.jason
```

# Functions

The following examples are made with the following `composer.json` in mind:

```json
{
    "extra": {
        "react-inspector": {
            "metrics": [
                "ticks.future.current",
                "ticks.future.total",
                "ticks.future.ticks",
                "signals.current",
                "signals.total",
                "signals.ticks"
            ],
            "reset": {
                "ticks": [
                    "ticks.future.ticks",
                    "signals.ticks"
                ]
            }
        },
        "reactive-apps": {
            "command": {
                "ReactiveApps\\Command\\HttpServer\\Command": "src/Command"
            },
            "config": [
                "etc/config/app.php",
                "etc/config/http-server.php",
                "etc/config/supervisor.php"
            ]
        }
    }
}
```

## GetInPackages::composer

Get the config for the given key for all packages that have it. Passed the Package instance as key and config from
`composer.json` as value:

```php
/**
 * @var Composed\Package $package
 * @var mixed            $config
 */
foreach (GetInPackages::composer('extra') as $package => $config) {
    $packagesConfig->add($package, $config);
}
```

## GetInPackages::fromComposer

Same as `GetInPackages::composer` but `yield from`'s the items in each package:

```php
foreach (GetInPackages::fromComposer('extra.react-inspector.metrics') as $item) {
    // $item: [
    // ticks.future.current,
    // ticks.future.total,
    // ticks.future.ticks,
    // signals.current,
    // signals.total,
    // signals.ticks,
    // ]
}
```

## GetInPackages::composerPath

Building on `GetInPackages::composer`, `GetInPackages::composer_path` iterates over all the config items per package
coming back from `GetInPackages::composer` and gets the full path for the given items value. This is handy for for
example configuration autodiscovery:

```php
foreach (GetInPackages::composerPath('extra.reactive-apps.config') as $path) {
    $config->loadFromFile($path);
}
```

## GetInPackages::composerWithPath

Works the same as `GetInPackages::composer_path` but instead of down a full path per item it use that full path for
the item as key and the items key and value. Handy for scanning a file directory and having the namespace for the root
present:

```php
foreach (GetInPackages::composerWithPath('extra.reactive-apps.command') as $path => $namespacePrefix) {
    $commands->scan($path, $namespacePrefix);
}
```

# License

The MIT License (MIT)

Copyright (c) 2025 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
