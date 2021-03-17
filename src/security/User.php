<?php
namespace braga\tools\security;
class User
{
	// -----------------------------------------------------------------------------------------------------------------
	protected $idUser;
	protected $login;
	protected $fullName;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(?string $idUser, ?string $login, ?string $fullName)
	{
		$this->idUser = $idUser;
		$this->login = $login;
		$this->fullName = $fullName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return \braga\tools\security\string
	 */
	public function getIdUser()
	{
		return $this->idUser;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return \braga\tools\security\string
	 */
	public function getLogin()
	{
		return $this->login;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return \braga\tools\security\string
	 */
	public function getFullName()
	{
		return $this->fullName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \braga\tools\security\string $idUser
	 */
	public function setIdUser($idUser)
	{
		$this->idUser = $idUser;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \braga\tools\security\string $login
	 */
	public function setLogin($login)
	{
		$this->login = $login;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \braga\tools\security\string $fullName
	 */
	public function setFullName($fullName)
	{
		$this->fullName = $fullName;
	}
	// -----------------------------------------------------------------------------------------------------------------
}
