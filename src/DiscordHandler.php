<?php

namespace DiscordHandler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use \Monolog\Logger;
use \Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DiscordHandler extends AbstractProcessingHandler
{
    /** @var Config */
    protected $config;

    /**
     * Colors for a given log level.
     *
     * @var array
     */
    protected $levelColors = [
        Logger::DEBUG => 10395294,
        Logger::INFO => 5025616,
        Logger::NOTICE => 6323595,
        Logger::WARNING => 16771899,
        Logger::ERROR => 16007990,
        Logger::CRITICAL => 16007990,
        Logger::ALERT => 16007990,
        Logger::EMERGENCY => 16007990,
    ];

    /**
     * DiscordHandler constructor.
     *
     * @param array|string $webHooks
     * @param string $name
     * @param string $subName
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($webHooks, $name = '', $subName = '', $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->config = (new Config())
            ->setClient(new Client())
            ->setName($name)
            ->setSubName($subName)
            ->setWebHooks($webHooks);
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     *
     * @return DiscordHandler
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param array $record
     *
     * @throws GuzzleException
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        if ($this->config->isEmbedMode()) {
            $parts = [[
                'embeds' => [
                    [
                        'title' => $record['level_name'],
                        'description' => $this->splitMessage($record['message'])[0],
                        'timestamp' => $record['datetime']->format($this->config->getDatetimeFormat()),
                        'color' => $this->levelColors[$record['level']],
                    ]
                ]
            ]];
        } else {
            $content = strtr(
                $this->config->getTemplate(),
                [
                    '{datetime}' => $record['datetime']->format($this->config->getDatetimeFormat()),
                    '{name}' => $this->config->getName(),
                    '{subName}' => $this->config->getSubName(),
                    '{levelName}' => $record['level_name'],
                    '{message}' => $record['message'],
                ]
            );
            $parts = array_map(function ($message) {
                return [
                    'content' => $message
                ];
            }, $this->splitMessage($content));
        }

        foreach ($this->config->getWebHooks() as $webHook) {
            foreach ($parts as $part) {
                $this->send($webHook, $part);
            }
        }
    }

    /**
     * @param string $content
     *
     * @return string[]
     */
    protected function splitMessage($content)
    {
        $maxMessageLength = $this->config->getMaxMessageLength();

        if (!$maxMessageLength) {
            return [$content];
        }

        if ($this->config->isMultiMsg()) {
            return str_split($content, $maxMessageLength);
        }

        if (strlen($content) > $maxMessageLength) {
            $truncated = strlen($content) - $maxMessageLength + 30;
            $result = substr($content, 0, $maxMessageLength - 30) . " [...truncated $truncated characters]";

            return [$result];
        }

        return [$content];
    }

    /**
     * @param string $webHook
     * @param string $json
     *
     * @throws GuzzleException
     */
    protected function send($webHook, $json)
    {
        $this->config->getClient()->request('POST', $webHook, [
            'json' => $json
        ]);
    }
}
