<?php

namespace braga\tools\process;

use braga\tools\process\exception\ProcessException;
/**
 * Created 21.02.2023 18:36
 * error prefix
 * @autor Tomasz Gajewski
 */
class ProcessController
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var ProcessCallback[][]
	 */
	protected $callbackAction = [];
	// -----------------------------------------------------------------------------------------------------------------
	public function addCallback(ProcessCallback $callback, string $event): void
	{
		$this->callbackAction[$event][] = $callback;
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function fireEvent(string $event, mixed ...$arg)
	{
		if(isset($this->callbackAction[$event]))
		{
			foreach($this->callbackAction[$event] as $callback)
			{
				try
				{
					$callback->call($this, ...$arg);
				}
				catch(ProcessException $e)
				{
					ProcessLogger::exception($e);
					$callback->fail($e, $this, ...$arg);
				}
			}
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}