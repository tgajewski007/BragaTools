<?php
namespace braga\tools\security;
class SecurityConfig
{
	// -----------------------------------------------------------------------------------------------------------------
	protected $key;
	protected $clientName;
	protected $issuedBy;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(?string $clientName, ?string $keyPemFormatContent, ?string $issuedBy)
	{
		$this->key = $keyPemFormatContent;
		$this->clientName = $clientName;
		$this->issuedBy = $issuedBy;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return \braga\tools\security\string
	 */
	public function getIssuedBy()
	{
		return $this->issuedBy;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \braga\tools\security\string $issuedBy
	 */
	public function setIssuedBy($issuedBy)
	{
		$this->issuedBy = $issuedBy;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return \braga\tools\security\string
	 */
	public function getKey()
	{
		return $this->key;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return \braga\tools\security\string
	 */
	public function getClientName()
	{
		return $this->clientName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \braga\tools\security\string $key
	 */
	public function setKey($key)
	{
		$this->key = $key;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \braga\tools\security\string $clientName
	 */
	public function setClientName($clientName)
	{
		$this->clientName = $clientName;
	}
	// -----------------------------------------------------------------------------------------------------------------
}
