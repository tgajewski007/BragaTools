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
		if($jwt instanceof Plain)
		{
			if($jwt->claims()->has("sub"))
			{
				$this->idUser = $jwt->claims()->get("sub");
			}
			elseif($jwt->claims()->has("uid"))
			{
				$this->idUser = $jwt->claims()->get("uid");
			}
			if($jwt->claims()->has("preferred_username"))
			{
				$this->login = $jwt->claims()->get("preferred_username");
			}
			if($jwt->claims()->has("name"))
			{
				$this->fullName = $jwt->claims()->get("name");
			}
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
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $firstName
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $lastName
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getIdUser()
	{
		return $this->idUser;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getLogin()
	{
		return $this->login;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getFullName()
	{
		return $this->fullName;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $idUser
	 */
	public function setIdUser($idUser)
	{
		$this->idUser = $idUser;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $login
	 */
	public function setLogin($login)
	{
		$this->login = $login;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $fullName
	 */
	public function setFullName($fullName)
	{
		$this->fullName = $fullName;
	}
	// -----------------------------------------------------------------------------------------------------------------
}
