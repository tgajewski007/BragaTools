<?php

namespace braga\tools\process;

use braga\tools\exception\BragaException;
/**
 * Created 09.12.2023 17:29
 * error prefix
 * @autor Tomasz Gajewski
 */
class ProcessInstancePersistance
{
	// -----------------------------------------------------------------------------------------------------------------
	public bool $initialized = false;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var EventProcess[]
	 */
	public array $evenstHandledStatus = [];
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var CallbackStepProcess[]
	 */
	public array $stepsProcededStatus = [];
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * Retrieves the steps of the process.
	 *
	 * @return CallbackStepProcess[] The steps of the process.
	 */
	public function getStepsProcededStatus(): array
	{
		return $this->stepsProcededStatus;
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function addEventHandled($eventName)
	{
		$this->evenstHandledStatus[$eventName] = new EventProcess();
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function addCallbackStep(ProcessCallback $callback)
	{
		$this->stepsProcededStatus[$callback::class] = new CallbackStepProcess();
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function setEventHandled(string $eventName)
	{
		if(isset($this->evenstHandledStatus[$eventName]))
		{
			$this->evenstHandledStatus[$eventName]->setHandled(true);
		}
		else
		{
			throw new BragaException("BR:85101 Zdarzenie " . $eventName . " nie zostało zdefiniowane", 85101);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function isEventHandled($eventName): bool
	{
		if(isset($this->evenstHandledStatus[$eventName]))
		{
			return $this->evenstHandledStatus[$eventName]->isHandled();
		}
		else
		{
			throw new BragaException("BR:85102 Zdarzenie " . $eventName . " nie zostało zdefiniowane", 85102);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function setStepProceeed(ProcessCallback $callback)
	{
		if(isset($this->stepsProcededStatus[$callback::class]))
		{
			$this->stepsProcededStatus[$callback::class]->setProceseed(true);
		}
		else
		{
			throw new BragaException("BR:85103 Krok " . $callback::class . " nie został zdefiniowany", 85103);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}