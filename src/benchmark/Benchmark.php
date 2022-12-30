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
		$this->events["#START"] = new Item("#START", JsonSerializer::toJson(["_SERVER" => $_SERVER, "_REQUEST" => $_REQUEST]));
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
		$index = toTag($mark) . "_" . strtoupper(getRandomStringLetterOnly(5));
		self::$instance->events[$index] = new Item($mark, $context);
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function __destruct()
	{
		$this->events["#END"] = new Item("#END");
		$basetime = $this->events["##START"]->timestamp;
		foreach($this->events as $event)
		{
			$event->duration = $event->timestamp - $basetime;
			$basetime = $event->timestamp;
		}
		$context = [];
		$context["Events"] = $this->events;
		$context["Duration"] = $this->events["#END"]->duration;
		$this->loggerClassNama::info("BENCHMARK: " . $this->events["#END"]->duration, $context);
	}
	// -----------------------------------------------------------------------------------------------------------------
}