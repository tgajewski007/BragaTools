<?php
namespace braga\tools\security;
class SecurityConfig
{
	// -----------------------------------------------------------------------------------------------------------------
	protected $key;
	protected $clientName;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(?string $clientName, ?string $keyPemFormatContent)
	{
		$this->key = $keyPemFormatContent;
		$this->clientName = $clientName;
	}
	// -----------------------------------------------------------------------------------------------------------------
}
