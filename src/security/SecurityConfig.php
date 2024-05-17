<?php
namespace braga\tools\security;
abstract class SecurityConfig
{
	// -----------------------------------------------------------------------------------------------------------------
	protected $clientName;
	protected $issuedBy;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(?string $clientName, ?string $issuedBy)
	{
		$this->clientName = $clientName;
		$this->issuedBy = $issuedBy;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getIssuedBy()
	{
		return $this->issuedBy;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $issuedBy
	 */
	public function setIssuedBy($issuedBy)
	{
		$this->issuedBy = $issuedBy;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $kid
	 */
	abstract public function getPublicKey($kid);
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getClientName()
	{
		return $this->clientName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $clientName
	 */
	public function setClientName($clientName)
	{
		$this->clientName = $clientName;
	}
	// -----------------------------------------------------------------------------------------------------------------
}
