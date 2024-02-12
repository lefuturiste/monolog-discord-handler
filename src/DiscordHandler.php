<?php

namespace DiscordHandler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Level;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DiscordHandler extends AbstractProcessingHandler
{
    /** @var Config */
    protected $config;

    /**
     * DiscordHandler constructor.
     *
     * @param array|string $webHooks
     * @param string $name
     * @param string $subName
     * @param Level $level
     * @param bool $bubble
     */
    public function __construct(
        array|string $webHook = [],
        string $name = '',
        string $subName = '',
        Level $level = Level::Debug,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);

        $this->config = (new Config())
            ->setClient(new Client())
            ->setName($name)
            ->setSubName($subName)
            ->setWebHooks(is_string($webHook) ? [$webHook]: $webHook);
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param Config $config
     *
     * @return DiscordHandler
     */
    public function setConfig(Config $config): self
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
                        'title' => $record->level->getName(),
                        'description' => $this->splitMessage($record->message)[0],
                        'timestamp' => $record->datetime->format($this->config->getDatetimeFormat()),
                        'color' => $this->getColorForLevel($record->level),
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
                    '{levelName}' => $record->level->getName(),
                    '{message}' => $record->message,
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
    protected function splitMessage(string $content): array
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
    protected function send(string $webHook, array $json): void
    {
        $this->config->getClient()->request('POST', $webHook, [
            'json' => $json
        ]);
    }

    /**
     * Return the embed colors for a given log level.
     *
     * @param Level $level
     * @return int
     */
    protected function getColorForLevel(Level $level): int {
        return match ($level) {
            Level::Debug => 10395294,
            Level::Info => 5025616,
            Level::Notice => 6323595,
            Level::Warning => 16771899,
            Level::Error => 16007990,
            Level::Critical => 16007990,
            Level::Alert => 16007990,
            Level::Emergency => 16007990,
        };
    }

}
