<?php
require '../vendor/autoload.php';

$log = new Monolog\Logger('name');

$log->pushHandler(new DiscordHandler\DiscordHandler([
    getenv("DISCORD_WEBHOOK_URL"),
], 'name', 'subname', 'DEBUG'));

$log->debug('test');
$log->info('test');
$log->notice('test');
$log->warning('test');
$log->error('test');
$log->critical('test');
$log->alert('test');
$log->emergency('test');
