<?php
namespace braga\tools\tools;
class RrsoCashFlow
{
	public $kwota;
	public $dzienSplaty;
	function __construct($kwota, $dzienSplaty)
	{
		$this->kwota = $kwota;
		$this->dzienSplaty = $dzienSplaty;
	}
}