<?php
require '../vendor/autoload.php';

$log = new Monolog\Logger('your name');

$log->pushHandler(new DiscordHandler\DiscordHandler([
'https://discordapp.com/api/webhooks/349907239303249930/QDPTQxUjaiD3wTrGH14eYa2jyVmdmxG1UNjaOAgP_lmqMEV_-KSq5kt7TG9A5A8GEO10',
], 'name', 'subname', 'DEBUG'));

$log->info('test');