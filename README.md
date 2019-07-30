# Monolog discord handler

[![Latest Stable Version](https://poser.pugx.org/lefuturiste/monolog-discord-handler/v/stable)](https://packagist.org/packages/lefuturiste/monolog-discord-handler)
[![Total Downloads](https://poser.pugx.org/lefuturiste/monolog-discord-handler/downloads)](https://packagist.org/packages/lefuturiste/monolog-discord-handler)
[![License](https://poser.pugx.org/lefuturiste/monolog-discord-handler/license)](https://packagist.org/packages/lefuturiste/monolog-discord-handler)
[![Monthly Downloads](https://poser.pugx.org/lefuturiste/monolog-discord-handler/d/monthly)](https://packagist.org/packages/lefuturiste/monolog-discord-handler)


A simple monolog handler for support Discord webhooks

-------------------------------------------------

### Dependencies

- PHP >= 5.3
- Monolog >= 1.3

-------------------------------------------------

## 1. Installing

Easy install via composer. Still no idea what composer is? Inform yourself [here](http://getcomposer.org).

```composer require lefuturiste/monolog-discord-handler```

-------------------------------------------------

## 2. Usage

Push this handler to your Monolog instance:

### Only one webhooks url

```php
<?php
require 'vendor/autoload.php';

$log = new Monolog\Logger('your name');

$log->pushHandler(new DiscordHandler\DiscordHandler('Discord Webhook url', 'name', 'subname', 'DEBUG'));

```

### Many webhooks url


```php
<?php
require 'vendor/autoload.php';

$log = new Monolog\Logger('your name');

$log->pushHandler(new DiscordHandler\DiscordHandler([
'Discord Webhook url 1',
'Discord Webhook url 2',
], 'name', 'subname', 'DEBUG'));

```

### Use configuration
 
```php
<?php
require 'vendor/autoload.php';

$log = new Monolog\Logger('your name');

$handler = new DiscordHandler\DiscordHandler('Webhook url','name', 'subname', 'DEBUG');

$handler->getConfig()
    ->setMultiMsg(true)
    ->setMaxMessageLength(2000) // at least 50 characters
    ->setDatetimeFormat('Y/m/d H:i')
    ->setTemplate("{datetime} {name}: {message}");

// or you can create another Config instance and replace it:
$otherConfig = new DiscordHandler\Config();
$otherConfig->setWebHooks(['Other Hook Url 1', 'Other Hook Url 2']);

$handler->setConfig($otherConfig);

$log->pushHandler($handler);

```
