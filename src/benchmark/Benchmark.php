<?php

namespace braga\tools\benchmark;

use braga\graylogger\BaseLogger;
use braga\tools\tools\JsonSerializer;
/**
 * Created 29.12.2022 18:37
 * error prefix
 * @autor Tomasz Gajewski
 */
class Benchmark
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var Item[]
	 */
	private array $events = [];
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var BaseLogger
	 */
	protected $loggerClassNama = BaseLogger::class;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct($loggerClassNama = null)
	{
		$this->events[] = new Item("#START", JsonSerializer::toJson(["_SERVER" => $_SERVER, "_REQUEST" => $_REQUEST]));
		if(!empty($loggerClassNama))
		{
			$this->loggerClassNama = new $loggerClassNama();
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function __destruct()
	{
		$this->events[] = new Item("#END");
		$this->loggerClassNama::info("BENCHMARK", $this->events);
	}
	// -----------------------------------------------------------------------------------------------------------------
}