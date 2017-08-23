<?php
namespace DiscordHandler;

use \Monolog\Logger;
use \Monolog\Handler\AbstractProcessingHandler;

class DiscordHandler extends AbstractProcessingHandler
{
	private $initialized = false;
	private $guzzle;

	private $name;
	private $subname;

	private $webhooks;
	private $statement;

	/**
	 * MonologDiscordHandler constructor.
	 * @param \GuzzleHttp\Client $guzzle
	 * @param bool $webhooks
	 * @param int $level
	 * @param bool $bubble
	 */
	public function __construct($webhooks, $name, $subname = '', $level = Logger::DEBUG, $bubble = true)
	{
		$this->name = $name;
		$this->subname = $subname;
		$this->guzzle = new \GuzzleHttp\Client();
		$this->webhooks = $webhooks;
		parent::__construct($level, $bubble);
	}

	/**
	 * @param array $record
	 */
	protected function write(array $record)
	{
		$content = '[' . $record['datetime']->format('Y-m-d H:i:s') . '] ' . $this->name . '.' . $this->subname . '.' . $record['level_name'] . ': ' . $record['message'];

		$i = 0;
		while ($i < count($this->webhooks)){
			$req = $this->guzzle->request('POST', $this->webhooks[$i], [
				'form_params' => [
					'content' => $content
				]
			]);
			$i++;
		}
	}
}