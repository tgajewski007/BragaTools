<?php

namespace braga\tools\process;

use braga\enginerule\iface\Testable;
/**
 * Created 21.02.2023 19:05
 * error prefix
 * @autor Tomasz Gajewski
 */
abstract class RuledCallback implements ProcessCallback
{
	// -----------------------------------------------------------------------------------------------------------------
	protected Testable $testable;
	protected $baseObject;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(Testable $testable, $baseObject)
	{
		$this->testable = $testable;
		$this->baseObject = $baseObject;
	}
	// -----------------------------------------------------------------------------------------------------------------
	final public function check(): bool
	{
		return $this->testable->test($this->baseObject);
	}
	// -----------------------------------------------------------------------------------------------------------------
}