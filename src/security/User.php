<?php
namespace braga\tools\security;
use Lcobucci\JWT\Token\Plain;
class User
{
	// -----------------------------------------------------------------------------------------------------------------
	protected $idUser;
	protected $login;
	protected $fullName;
	protected $firstName;
	protected $lastName;
	protected $email;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(?Plain $jwt)
	{
		if($jwt->claims()->has("sub"))
		{
			$this->idUser = $jwt->claims()->get("sub");
		}
		elseif($jwt->claims()->has("uid"))
		{
			$this->idUser = $jwt->claims()->get("uid");
		}
		$this->login = $jwt->claims()->get("preferred_username");
		$this->fullName = $jwt->claims()->get("name");
		if($jwt->claims()->has("email"))
		{
			$this->email = $jwt->claims()->get("email");
		}
		if($jwt->claims()->has("family_name"))
		{
			$this->lastName = $jwt->claims()->get("family_name");
		}
		if($jwt->claims()->has("given_name"))
		{
			$this->firstName = $jwt->claims()->get("given_name");
		}
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
