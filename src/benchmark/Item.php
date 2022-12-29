<?php

namespace braga\tools\benchmark;

/**
 * Created 29.12.2022 18:38
 * error prefix
 * @autor Tomasz Gajewski
 */
class Item
{
	private int $timestamp;
	private string $mark;
	private ?string $context;
	public function __construct($mark, $context = null)
	{
		$this->timestamp = time();
		$this->mark =$mark;
		$this->context =$context;
	}
}