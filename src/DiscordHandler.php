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

    /**
     * Флаг мульти-сообщений (Если true, то при превышении максимальной длины одного сообщения, будет отправлено несколько)
     * @var bool
     */
    protected $multiMsg;

    /** @var array */
    protected $webHooks;

    /**
     * DiscordHandler constructor.
     *
     * @param $webHooks
     * @param $name
     * @param bool $multiMsg
     * @param string $subName
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($webHooks, $name, $subName = '', $level = Logger::DEBUG, $bubble = true, $multiMsg = false)
    {
        $this->name = $name;
        $this->subName = $subName;
        $this->guzzle = new \GuzzleHttp\Client();
        $this->webHooks = (array)$webHooks;
        $this->multiMsg = $multiMsg;
        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function write(array $record)
    {
        $content = '[**' . $record['datetime']->format('Y-m-d H:i:s') . ']** ' . $this->name . '.' . $this->subName . '.__' . $record['level_name'] . '__: ' . $record['message'];

        foreach ($this->webHooks as $webHook) {
            if ($this->multiMsg) {
                foreach (str_split($content, 2000) as $frame) {
                    $this->send($webHook, $frame);
                }
            } else {
                if (strlen($content) > 2000) {
                    $truncated = strlen($content) - 1950;
                    $content = substr($content, 0, 1950) . " [...truncated $truncated characters]";
                }
                $this->send($webHook, $content);
            }
        }
    }

    /**
     * @param $webHook
     * @param $content
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function send($webHook, $content){
        $this->guzzle->request(
            'POST', $webHook, [
                'form_params' => [
                    'content' => $content
                ]
            ]
        );
    }
}