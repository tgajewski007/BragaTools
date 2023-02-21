<?php

namespace braga\tools\process;

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
	public function fireEvent(string $event)
	{
		if(isset($this->callbackAction[$event]))
		{
			foreach($this->callbackAction[$event] as $callback)
			{
				if($callback->check())
				{
					$callback->success();
				}
				else
				{
					$callback->fail();
				}
			}
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}