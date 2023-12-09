<?php

namespace braga\tools\process;

use braga\tools\exception\BragaException;
use braga\tools\process\exception\CantProcessEventException;
use braga\tools\process\exception\CantProcessStepException;
use braga\tools\process\exception\ProcessException;
use braga\tools\tools\JsonSerializer;
/**
 * Created 21.02.2023 18:36
 * error prefix
 * @autor Tomasz Gajewski
 */
class ProcessController
{
	// -----------------------------------------------------------------------------------------------------------------
	protected ?ProcessInstancePersistance $processInstance;
	// -----------------------------------------------------------------------------------------------------------------
	public function getPersistanceProcessInstance(): ProcessInstancePersistance
	{
		$this->processInstance->initialized = true;
		return $this->processInstance;
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function restorePersistanceProcessInstance(ProcessInstancePersistance $processInstance): void
	{
		$this->processInstance = $processInstance;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var ProcessCallback[][]
	 */
	protected $callbackAction = [];
	// -----------------------------------------------------------------------------------------------------------------
	public function addCallback(ProcessCallback $callback, string $event): void
	{
		if(empty($this->processInstance))
		{
			$this->processInstance = new ProcessInstancePersistance();
		}
		$this->callbackAction[$event][] = $callback;
		if(!$this->processInstance->initialized)
		{
			$this->processInstance->addCallbackStep($callback);
			$this->processInstance->addEventHandled($event);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function fireEvent(string $event, mixed ...$arg)
	{
		if(isset($this->callbackAction[$event]))
		{
			try
			{
				$this->canProcessEvent($event);
				foreach($this->callbackAction[$event] as $callback)
				{
					ProcessLogger::debug("FireEvent: " . $event, [ "event" => $event, "classname" => get_class($callback) ]);
					try
					{
						$callback->call($this, ...$arg);
					}
					catch(ProcessException $e)
					{
						ProcessLogger::exception($e);
						$callback->fail($e, $this, ...$arg);
					}
					$this->processInstance->setStepProceeed($callback);
				}
			}
			catch(CantProcessEventException $e)
			{
				ProcessLogger::exception($e);
				$callback->onCantProcessEvent($e, $this, ...$arg);
			}
			$this->setEventHandled($event);
			$this->setCallbackStepProceded($callback);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return void
	 * @throws CantProcessEventException|BragaException
	 */
	protected function canProcessEvent(string $eventName): void
	{
		if(!isset($this->processInstance))
		{
			throw new BragaException("Nie ustawiono instancji procesu");
		}
		if($this->processInstance->isEventHandled($eventName))
		{
			throw new CantProcessEventException("Zdarzenie " . $eventName . " zostało już wcześniej obsłużone");
		}

		$dependences = $this->getDependences($eventName);

		$stepsProcededStatus = $this->processInstance->getStepsProcededStatus();
		foreach($stepsProcededStatus as $className => $step)
		{
			$var1 = isset($dependences[$className]);
			$var2 = $step->isProceseed();
			if(!($var1 xor $var2))
			{
				ProcessLogger::error("Brak spełnienia zależności: " . $className . " => " . $step->isProceseed(), [ "dependeces" => JsonSerializer::toJson($dependences), "stepsStatus" => JsonSerializer::toJson($stepsProcededStatus) ]);
				throw new CantProcessStepException("Krok: (" . $className . ") nie może zostać wykonany z powodu niespełnionych zależności");
			}
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function setEventHandled(string $event)
	{
		$this->processInstance->setEventHandled($event);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $eventName
	 * @return array classname in key on array
	 */
	protected function getDependences(string $eventName): array
	{
		$retval = [];
		if(isset($this->callbackAction[$eventName]))
		{
			foreach($this->callbackAction[$eventName] as $callback)
			{
				foreach($callback->getCallbackDependecy() as $callbackClassNameDependeces)
				{
					$retval[$callbackClassNameDependeces] = 1;
				}
			}
		}
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function setCallbackStepProceded(ProcessCallback $callback)
	{
		$this->processInstance->setStepProceeed($callback);
	}
	// -----------------------------------------------------------------------------------------------------------------
}