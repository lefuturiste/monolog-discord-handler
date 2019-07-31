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

Easy installation via composer. Still no idea what composer is? Find out here [here](http://getcomposer.org).

```composer require lefuturiste/monolog-discord-handler```

-------------------------------------------------

## 2. Usage

Push this handler to your Monolog instance:

### Single webhook URL

```php
<?php
require 'vendor/autoload.php';

$log = new Monolog\Logger('your name');

$log->pushHandler(new DiscordHandler\DiscordHandler('https://discordapp.com/api/webhooks/xxx/yyy', 'name', 'subname', 'DEBUG'));

```

### Multiple webhook URLs


```php
<?php
require 'vendor/autoload.php';

$log = new Monolog\Logger('your name');

$log->pushHandler(new DiscordHandler\DiscordHandler([
  'https://discordapp.com/api/webhooks/xxx/yyy',
  'https://discordapp.com/api/webhooks/xxx/yyy'
], 'name', 'subname', 'DEBUG'));

```

### Use configuration

You can customize the default message and datetime format.
 
```php
<?php
require 'vendor/autoload.php';

$log = new Monolog\Logger('name');

$handler = new DiscordHandler\DiscordHandler('https://discordapp.com/api/webhooks/xxx/yyy', 'name', 'subname', 'DEBUG');

$handler->getConfig()
    ->setMultiMsg(true)
    ->setMaxMessageLength(2000) // at least 50 characters
    ->setDatetimeFormat('Y/m/d H:i')
    ->setTemplate("{datetime} {name}: {message}");

// or you can create another Config instance and replace it:
$otherConfig = new DiscordHandler\Config();
$otherConfig->setWebHooks([
  'https://discordapp.com/api/webhooks/xxx/yyy', 
  'https://discordapp.com/api/webhooks/xxx/yyy'
]);

$handler->setConfig($otherConfig);

$log->pushHandler($handler);
```
