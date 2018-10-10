<?php

namespace DiscordHandler;

use \Monolog\Logger;
use \Monolog\Handler\AbstractProcessingHandler;

class DiscordHandler extends AbstractProcessingHandler
{
    /** @var \GuzzleHttp\Client */
    protected $guzzle;

    protected $name;
    protected $subName;

    /** @var array */
    protected $webHooks;

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
        $this->name = $name;
        $this->subName = $subName;
        $this->guzzle = new \GuzzleHttp\Client();
        $this->webHooks = (array)$webHooks;
        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function write(array $record)
    {
        $content = '[**' . $record['datetime']->format('Y-m-d H:i:s') . ']** ' . $this->name . '.' . $this->subName . '.__' . $record['level_name'] . '__: ' . $record['message'];

        if (strlen($content) > 2000) {
            $truncated = strlen($content) - 1950;
            $content = substr($content, 0, 1950) . " [...truncated $truncated characters]";
        }
        foreach ($this->webHooks as $webHook) {
            $this->guzzle->request(
                'POST', $webHook, [
                    'form_params' => [
                        'content' => $content
                    ]
                ]
            );
        }
    }
}