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
	/**
	 * @var Benchmark
	 */
	private static ?Benchmark $instance;
	// -----------------------------------------------------------------------------------------------------------------
	private function __construct($loggerClassNama = null)
	{
		if(!empty($loggerClassNama))
		{
			$this->loggerClassNama = new $loggerClassNama();
		}
		self::add("#START", JsonSerializer::toJson(["_SERVER" => $_SERVER, "_REQUEST" => $_REQUEST]));
	}
	// -----------------------------------------------------------------------------------------------------------------
	public static function init($loggerClassNama = null)
	{
		if(empty(self::$instance))
		{
			self::$instance = new self($loggerClassNama);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public static function add($mark, $context = null)
	{
		self::$instance->events[] = new Item($mark,$context);
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function __destruct()
	{
		self::add("#END");

		$table = "";
		$basetime = current($this->events)->timestamp;
		foreach($this->events as $event)
		{
			$event->duration = $event->timestamp - $basetime;
			$basetime = $event->timestamp;
		}
		
		
		$this->loggerClassNama::info("BENCHMARK", $this->events);
	}
	// -----------------------------------------------------------------------------------------------------------------
}