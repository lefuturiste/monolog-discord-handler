<?php

namespace DiscordHandler\Tests\Helpers;

use GuzzleHttp\Client;

class DiscordHelper {
    private $token;
    private $guildId;
    private $channelId;
    private $client;

    public function __construct($token, $guildId) {
        $this->token = $token;
        $this->guildId = $guildId;
        $this->client = new Client([
            'base_uri' => 'https://discordapp.com',
            'http_errors' => true,
            'headers' => [
                'Authorization' => 'Bot ' . $this->token
            ]
        ]);
    }

    public function fetchChannelIdFromName($channelName)
    {
        $response = $this->client->get(
            '/api/guilds/' . $this->guildId . '/channels'
        );
        $body = $this->parseBody($response);
        $this->channelId = array_values(array_filter($body, function($c) use ($channelName) {
            return $c['name'] === $channelName;
        }))[0]['id'];

        return $this->channelId;
    }

    public function getLastMessage()
    {
        $response = $this->client->get(
            '/api/channels/' . $this->channelId . '/messages'
        );
        $body = $this->parseBody($response);
        return array_values($body)[0];
    }

    private function parseBody($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;
    }

    public function getGuildId()
    {
        return $this->guildId;
    }

    public function getToken()
    {
        return $this->token;
    }
}