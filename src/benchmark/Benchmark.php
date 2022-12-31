<?php

namespace braga\tools\benchmark;

use braga\graylogger\BaseLogger;
use braga\tools\tools\JsonSerializer;
use Throwable;
/**
 * Created 29.12.2022 18:37
 * error prefix
 * @autor Tomasz Gajewski
 */
class Benchmark
{
	// -----------------------------------------------------------------------------------------------------------------
	private const END_INDEX = 9999999;
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
		try
		{
			if(!empty($loggerClassNama))
			{
				$this->loggerClassNama = new $loggerClassNama();
			}
			$this->events[] = new Item("#START");
		}
		catch(Throwable $e)
		{
			$this->loggerClassNama::exception($e);
		}
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
		try
		{
			self::$instance->events[] = new Item($mark, $context);
		}
		catch(Throwable $e)
		{
			self::$instance->loggerClassNama::exception($e);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function __destruct()
	{
		try
		{
			$this->events[self::END_INDEX] = new Item("#END");
			$basetime = current($this->events)->timestamp;
			foreach($this->events as $event)
			{
				$event->duration = number_format($event->timestamp - $basetime, 6, ".", "");
				$basetime = $event->timestamp;
			}
			$context = [];
			$context["Events"] = JsonSerializer::toJson($this->events);
			$context["Duration"] = $this->events[self::END_INDEX]->duration;
			$context["_REQUEST"] = JsonSerializer::toJson($_REQUEST);
			$this->loggerClassNama::info("BENCHMARK: " . $this->events[self::END_INDEX]->duration, $context);
		}
		catch(Throwable $e)
		{
			$this->loggerClassNama::exception($e);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}