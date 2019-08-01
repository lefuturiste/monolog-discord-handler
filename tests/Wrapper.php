<?php

namespace DiscordHandler\Tests;

use DiscordHandler\Tests\Helpers\DiscordHelper;

class Wrapper extends \PHPUnit\Framework\TestCase
{
    private static $discordHelper = NULL;

    protected static $webhookUrl = "";

    private static $botToken = "";

    private static $guildId = "";

    private static $textChannelId = "";

    private static $textChannelName = "";

    protected function beforeTest() {
        $dotEnvPath = dirname(__DIR__);
        \Dotenv\Dotenv::create($dotEnvPath)->load();
        self::$webhookUrl = getenv('DISCORD_WEBHOOK_URL');
        self::$botToken = getenv('DISCORD_BOT_TOKEN');
        self::$guildId = getenv('DISCORD_GUILD_ID');
        self::$textChannelName = getenv('DISCORD_TEXT_CHANNEL_NAME', '');
        self::$textChannelId = getenv('DISCORD_TEXT_CHANNEL_ID', '');
    }
    
    protected function getDiscordHelper()
    {
        if (self::$discordHelper === NULL) {
            self::$discordHelper = new DiscordHelper(
                self::$botToken,
                self::$guildId
            );
            if (self::$textChannelName !== '') {
                self::$discordHelper->fetchChannelIdFromName(self::$textChannelName); 
            } else {
                self::$discordHelper->setChannelId(self::$textChannelId); 
            }
        }
        return self::$discordHelper;
    }
}
