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
	protected ?Testable $testable = null;
	protected $baseObject = null;
	// -----------------------------------------------------------------------------------------------------------------
	public function init(Testable $testable, $baseObject)
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