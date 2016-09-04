<?php
namespace braga\tools\tools;
/**
 * Created on 17-10-2011 22:01:45
 * @author Tomasz Gajewski
 * @package common
 * error prefix EM:107
 */
class PostChecker
{
	// -------------------------------------------------------------------------
	private static $instance = null;
	// -------------------------------------------------------------------------
	static function getAll()
	{
		return self::$instance;
	}
	// -------------------------------------------------------------------------
	static function get($key)
	{
		if(isset(self::$instance[$key]))
		{
			return self::$instance[$key];
		}
		else
		{
			return null;
		}
	}
	// -------------------------------------------------------------------------
	static function set($key, $value)
	{
		self::$instance[$key] = $value;
	}
	// -------------------------------------------------------------------------
	protected function logAction(BaseAction $objAction)
	{
		$u = Uzytkownik::getCurrent();
		if($u instanceof Uzytkownik)
		{
			$l = Log::get();
			$l->setAction($objAction->action);
			$l->setArg1($objAction->arg1);
			$l->setArg2($objAction->arg2);
			$l->setArg3($objAction->arg3);
			$l->setCreateDate(date(PHP_DATETIME_FORMAT));
			$l->setIdUzytkownik($u->getIdUzytkownik());
			$l->setIpAdres($_SERVER["REMOTE_ADDR"]);
			$l->setVariables(var_export($objAction->post, true));
			$l->setIdModul(Modul::getCurrent()->getIdModul());
			$l->save();
		}
	}
	// -------------------------------------------------------------------------
	public function checkPost(BaseAction $objAction)
	{
		$daneG = $this->preCheckVal($_GET, "GET");
		$daneP = $this->preCheckVal($_POST, "POST");

		if(is_array($daneG) && is_array($daneP))
		{
			$dane = array_merge($daneG, $daneP);
		}
		else if(is_array($daneG))
		{
			$dane = $daneG;
		}
		else if(is_array($daneP))
		{
			$dane = $daneP;
		}
		else
		{
			$dane = null;
		}

		$objAction->post = $dane;
		self::$instance = $dane;
		// ================= GET =================
		if(isset($dane["action"]))
		{
			$objAction->action = $dane["action"];
		}
		if(isset($dane["arg1"]))
		{
			$objAction->arg1 = $dane["arg1"];
		}
		if(isset($dane["arg2"]))
		{
			$objAction->arg2 = $dane["arg2"];
		}
		if(isset($dane["arg3"]))
		{
			$objAction->arg3 = $dane["arg3"];
		}
		if(isset($dane["js"]))
		{
			$objAction->js = true;
		}
		// ============================================
		if($objAction->action != "")
		{
			$this->logAction($objAction);
		}
	}
	// -------------------------------------------------------------------------
	protected function preCheckVal($array, $argName)
	{
		$retval = array();
		foreach($array as $name => $val)
		{
			$name = strtolower($name);
			$retval[$name] = $this->checkVal($val, $argName . "[" . $name . "]");
		}
		return $retval;
	}
	// -------------------------------------------------------------------------
	protected function checkVal($napis, $argName)
	{
		$retval = "";
		if(!is_array($napis))
		{
			$retval = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $napis);
			$retval = htmlspecialchars($retval, ENT_QUOTES, "UTF-8");
			$retval = trim($retval);
		}
		else
		{
			foreach($napis as $klucz => $wartosc)
			{
				$klucz = strtolower($klucz);
				$retval[$klucz] = $this->checkVal($wartosc, $argName);
			}
		}
		return $retval;
	}
	// -------------------------------------------------------------------------
}
?>