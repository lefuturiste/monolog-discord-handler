<?php
require '../vendor/autoload.php';

$log = new Monolog\Logger('name');

$log->pushHandler(new DiscordHandler\DiscordHandler([
    'https://discordapp.com/api/webhooks/xxx/yyy',
], 'name', 'subname', 'DEBUG'));

$log->debug('test');
$log->info('test');
$log->notice('test');
$log->warning('test');
$log->error('test');
$log->critical('test');
$log->alert('test');
$log->emergency('test');
