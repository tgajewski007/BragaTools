<?php

namespace braga\tools\api\types\type;

/**
 * Created 28.06.2023 21:31
 * error prefix
 * @autor Tomasz Gajewski
 */
class KeyValueType
{
	public function __construct(public ?string $key = null, public ?string $value = null)
	{
	}
}