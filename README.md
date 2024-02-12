# Monolog discord handler

[![Latest Stable Version](https://poser.pugx.org/lefuturiste/monolog-discord-handler/v/stable)](https://packagist.org/packages/lefuturiste/monolog-discord-handler)
[![Total Downloads](https://poser.pugx.org/lefuturiste/monolog-discord-handler/downloads)](https://packagist.org/packages/lefuturiste/monolog-discord-handler)
[![License](https://poser.pugx.org/lefuturiste/monolog-discord-handler/license)](https://packagist.org/packages/lefuturiste/monolog-discord-handler)
[![Monthly Downloads](https://poser.pugx.org/lefuturiste/monolog-discord-handler/d/monthly)](https://packagist.org/packages/lefuturiste/monolog-discord-handler)

A simple monolog handler for support Discord webhooks

### Dependencies

- PHP >= 8.1
- Monolog >= 3.0

**If you want to use this lib with older version of PHP, install versions prior to 0.4** 

## 1. Installing

Easy installation via composer. Still no idea what composer is? Find out here [here](http://getcomposer.org).

```
composer require lefuturiste/monolog-discord-handler
```

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

### 3. Run examples

To run the example, you need:
- Clone this repo
- Run `composer install` at the root
- Change directory into `examples`
- Run with the `DISCORD_WEBHOOK_URL` env var set: `env DISCORD_WEBHOOK_URL=https://… php ./simple_usage.php`

### 4. Contribute

- `composer install`
- `cp .env.example .env`
- ~~`composer test`~~, for now we don't have unit tests.
- run `vendor/bin/phpstan analyse src -l 6` to run the static analyzer.

#### TODO

- [x] upgrade to monolog 3.0
- [ ] upgrade to php 8.1
- [ ] use type safe feature of php 8.1
- [ ] delete outdated "units" tests that are in fact integration tests
- [ ] add simple units test that will test for interfaces

