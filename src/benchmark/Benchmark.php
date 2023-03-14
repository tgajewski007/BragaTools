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
	private const START_INDEX = 0;
	private const INIT_INDEX = 1;
	private const END_INDEX = 99999999999;
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
	protected ?int $maxItem = null;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var Benchmark
	 */
	private static ?Benchmark $instance;
	// -----------------------------------------------------------------------------------------------------------------
	private function __construct($loggerClassNama = null, $maxItem = 100)
	{
		try
		{
			if(!empty($loggerClassNama))
			{
				$this->maxItem = $maxItem;
				$this->loggerClassNama = new $loggerClassNama();
			}
			$this->events[self::START_INDEX] = new Item("#REQUEST_TIME");
			$this->events[self::START_INDEX]->timestamp = $_SERVER["REQUEST_TIME_FLOAT"];
			$this->events[self::INIT_INDEX] = new Item("#INIT");
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
			if(count(self::$instance->events) < self::$instance->maxItem)
			{
				self::$instance->events[] = new Item($mark, $context);
			}
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
			$firstEvent = $this->events[self::START_INDEX];
			$startTime = $firstEvent->timestamp;
			$baseTime = $firstEvent->timestamp;
			foreach($this->events as $event)
			{
				$event->duration = number_format($event->timestamp - $baseTime, 9, ".", "");
				$event->progres = number_format($event->timestamp - $startTime, 9, ".", "");
				$baseTime = $event->timestamp;
			}
			$context = [];
			$context["Events"] = JsonSerializer::toJson($this->events);
			$context["Duration"] = floatval($this->events[self::END_INDEX]->progres);
			$context["_REQUEST"] = JsonSerializer::toJson($_REQUEST);
			$this->loggerClassNama::info("BENCHMARK: " . $this->events[self::END_INDEX]->progres, $context);
		}
		catch(Throwable $e)
		{
			$this->loggerClassNama::exception($e);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}