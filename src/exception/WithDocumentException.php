<?php

namespace braga\tools\exception;

/**
 * Created 12.07.2023 17:21
 * error prefix
 * @autor Tomasz Gajewski
 */
class WithDocumentException extends BragaException
{
	public ?int $idBerkas = null;
}