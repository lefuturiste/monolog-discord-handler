<?php

namespace DiscordHandler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use \Monolog\Logger;
use \Monolog\Handler\AbstractProcessingHandler;

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
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($webHooks, $name, $subName = '', $level = Logger::DEBUG, $bubble = true)
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
     */
    protected function write(array $record)
    {
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

        $parts = $this->splitMessage($content);
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
     * @param string $content
     *
     * @throws GuzzleException
     */
    protected function send($webHook, $content)
    {
        $this->config->getClient()->request('POST', $webHook, ['form_params' => ['content' => $content]]);
    }
}
