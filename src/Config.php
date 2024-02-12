<?php

namespace DiscordHandler;

use GuzzleHttp\Client;
use InvalidArgumentException;

class Config
{
    protected Client $client;

    protected string $name;

    protected string $subName;

    protected bool $multiMsg = false;

    protected int $maxMessageLength = 255;

    protected array $webHooks = [];

    protected string $template = "**[{datetime}]** {name}.{subName}.__{levelName}__: {message}";

    protected string $datetimeFormat = "Y-m-d H:i:s";

    /** @var bool */
    private $embedMode = false;

    /**
     * Http client, performing interaction with Discord API.
     *
     * @return Client
     */
    public function getClient(): Client
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * @param Client $client
     *
     * @return $this
     *
     * @see getClient()
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     *
     * @see getName()
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     *
     * @see getSubName()
     */
    public function getSubName(): string
    {
        return $this->subName;
    }

    /**
     * @param string $subName
     *
     * @return $this
     *
     * @see getSubName()
     */
    public function setSubName(string $subName): self
    {
        $this->subName = $subName;

        return $this;
    }

    /**
     * Multi message flag. Works only when __maxMessageLength__ param is set.
     * If length of the message is more than maxMessageLength,
     * then message will be splitted into multi and sent as separated different messages.
     *
     * @return bool
     *
     * @see getMaxMessageLength()
     */
    public function isMultiMsg(): bool
    {
        return $this->multiMsg;
    }

    /**
     * @param bool $multiMsg
     *
     * @return Config
     *
     * @see isMultiMsg()
     * @see setMaxMessageLength()
     */
    public function setMultiMsg(int $multiMsg): self
    {
        $this->multiMsg = $multiMsg;

        return $this;
    }

    /**
     * @param int|null $maxMessageLength
     *
     * @return Config
     *
     * @see getMaxMessageLength()
     */
    public function setMaxMessageLength(int $maxMessageLength): self
    {
        if (!is_null($maxMessageLength)) {
            if (!is_int($maxMessageLength)) {
                throw new InvalidArgumentException(
                    'maxMessageLength expected to be positive integer or null, ' .
                    gettype($maxMessageLength) . ' passed.'
                );
            }

            if ($maxMessageLength < 50) {
                throw new InvalidArgumentException('maxMessageLength must be at least 50 characters.');
            }
        }

        $this->maxMessageLength = $maxMessageLength;

        return $this;
    }

    /**
     * Maximum message length.
     * If __multiMsg__ param is set to true,
     * then message will be splitted into multiple and sent as separated different messages.
     * Otherwise message will be truncated.
     *
     * @return int
     *
     * @see isMultiMsg()
     */
    public function getMaxMessageLength(): int
    {
        return $this->maxMessageLength;
    }

    /**
     * Discord web hook Url(s).
     *
     * @return string[]
     */
    public function getWebHooks(): array
    {
        return $this->webHooks;
    }

    /**
     * @param string|string[] $webHooks
     *
     * @return Config
     *
     * @see getWebHooks()
     */
    public function setWebHooks(array $webHooks): self
    {
        $this->webHooks = (array)$webHooks;

        return $this;
    }

    /**
     * Template of message, sent to Discord.
     *
     * Next placeholders are available:
     * - {datetime} - will be replaced by Log datetime.
     * - {name} - will be replaced by __name__ configuration parameter.
     * - {subName} - will be replaced by __subName__ configuration parameter.
     * - {levelName} - will be replaced by level name of Log message.
     * - {message} - will be replaced by Log message text.
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return Config
     *
     * @see getTemplate()
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Format of datetime, used when assembling message.
     * Accepts parameters, used in date() format.
     *
     * @return string
     *
     * @see date()
     * @see getTemplate()
     */
    public function getDatetimeFormat(): string
    {
        return $this->datetimeFormat;
    }

    /**
     * @param string $datetimeFormat
     *
     * @return Config
     *
     * @see getDatetimeFormat()
     */
    public function setDatetimeFormat($datetimeFormat): self
    {
        $this->datetimeFormat = $datetimeFormat;
        return $this;
    }

    /**
     * Enable or disable embed mode
     *
     * @param bool $value
     */
    public function setEmbedMode(bool $value = true): self
    {
        $this->embedMode = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmbedMode(): bool
    {
        return $this->embedMode;
    }
}
