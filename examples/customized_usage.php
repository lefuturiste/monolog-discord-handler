<?php
require '../vendor/autoload.php';

$log = new Monolog\Logger('name');

$handler = new DiscordHandler\DiscordHandler(
    getenv("DISCORD_WEBHOOK_URL"),
    'name',
    'subname',
    'DEBUG'
);

$handler->getConfig()
    ->setMultiMsg(true)
    ->setMaxMessageLength(2000) // at least 50 characters
    ->setDatetimeFormat('Y/m/d H:i')
    ->setTemplate("{datetime} {name}: {message}");

if (false) {
    // or you can create another Config instance and replace it:
    $otherConfig = new DiscordHandler\Config();
    $otherConfig->setWebHooks([
        'https://discordapp.com/api/webhooks/xxx/yyy',
        'https://discordapp.com/api/webhooks/xxx/yyy'
    ]);
    $handler->setConfig($otherConfig);
}

$log->pushHandler($handler);

$log->info("The quick brown fox jumps over the lazy dog");

