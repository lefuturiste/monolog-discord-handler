<?php

namespace DiscordHandler\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Monolog\Logger;
use DiscordHandler\Tests\Helpers\DiscordHelper;

class DiscordHandlerTest extends Wrapper {

    public function testSimpleWebhook()
    {
        $this->beforeTest();
        $discordHelper = $this->getDiscordHelper();
        $logger = new Logger('name');
        $logger->pushHandler(new \DiscordHandler\DiscordHandler(
            self::$webhookUrl,
            'foo',
            'bar',
            'DEBUG'
        ));
        $message = uniqid();
        $time = date('Y-m-d H:i:s');
        $logger->warning($message);

        $this->assertEquals(
            '**[' . $time . ']** foo.bar.__WARNING__: ' . $message,
            $discordHelper->getLastMessage()['content']
        );
    }

}
