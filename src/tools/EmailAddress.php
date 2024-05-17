<?php
namespace braga\tools\tools;
use braga\tools\exception\BadEmailAdress;

/**
 * Created on 14 wrz 2013 19:02:13
 * @author Tomasz Gajewski
 * @package frontoffice
 * error prefix HT:100
 * EmailAddress
 * klasa przeznaczona do przechowywania adresów emailowych
 * wykorzystywanych do wysyłania poczty poprzez SMTP
 */
class EmailAddress
{
	// -------------------------------------------------------------------------
	protected $email;
	protected $fullName;
	// -------------------------------------------------------------------------
	/**
	 * uwaga w konstruktorze należy podać obie wartość aby
	 * dokonała się inicjalizaca w przypadku nie podania obu wartości
	 * należy ręcznie te wartości przypisać używająć metod set*
	 */
	public function __construct($fullName, $email)
	{
		if($email != null)
		{
			$this->setEmail($email);
			$this->setFullName($fullName);
		}
		else
		{
			throw new BadEmailAdress("BT:10301 Błąd utworzenia obiektu Email. Adres email jest wymagany {" . $fullName . "}");
		}
	}
	// -------------------------------------------------------------------------
	public function setEmail($email)
	{
		$this->email = substr($email, 0, 255);
	}
	// -------------------------------------------------------------------------
	public function setFullName($fullName)
	{
		$this->fullName = mb_substr($fullName, 0, 255);
	}
	// -------------------------------------------------------------------------
	public function getEmail()
	{
		return $this->email;
	}
	// -------------------------------------------------------------------------
	public function getFullName()
	{
		return $this->fullName;
	}
	// -------------------------------------------------------------------------
	/**
	 * @param string $string
	 * @return EmailAddress
	 */
	public static function convert($string)
	{
		$string = trim($string);
		$string = html_entity_decode($string, ENT_QUOTES);
		$mark1 = mb_stripos($string, "<");
		$mark2 = mb_stripos($string, ">");
		$fullName = mb_substr($string, 0, $mark1);
		$email = mb_substr($string, $mark1 + 1, -1);
		return new EmailAddress($fullName, $email);
	}
	// -------------------------------------------------------------------------
	/**
	 * getFormatedAddress
	 * zwraca sformatowany adres zgodny ze standardami POP3
	 * fullname&lt;email&gt;
	 */
	public function getFormatedAddress()
	{
		return $this->fullName . "<" . $this->email . ">";
	}
	// -------------------------------------------------------------------------
}
?>