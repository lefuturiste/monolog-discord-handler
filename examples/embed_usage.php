<?php
require '../vendor/autoload.php';

$log = new Monolog\Logger('name');

$handler = new DiscordHandler\DiscordHandler(
    getenv("DISCORD_WEBHOOK_URL"),
);

$handler->getConfig()->setEmbedMode();

$log->pushHandler($handler);

$log->info("Hello world");
