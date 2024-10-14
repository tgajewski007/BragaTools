<?php
namespace braga\tools\tools;
/**
 * Created on 21-03-2012 18:12:53
 * @author Tomasz Gajewski
 * @package package_name
 * error prefix
 */
class Message
{
	// -----------------------------------------------------------------------------------------------------------------
	const MESSAGE_INFO = "MI";
	const MESSAGE_WARNING = "MW";
	const MESSAGE_ALERT = "MA";
	const MESSAGE_SQL = "MS";
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * @var IMessagePersistance
	 */
	private static $persistance;
	// -----------------------------------------------------------------------------------------------------------------
	public static function setPersistance(IMessagePersistance $p)
	{
		self::$persistance = $p;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * @var Message
	 */
	private static $instance;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * @var Message[][]
	 */
	protected $dataMessage = [];
	// -----------------------------------------------------------------------------------------------------------------
	private function __construct()
	{
		if(empty(self::$instance))
		{
			if(self::$persistance instanceof IMessagePersistance)
			{
				self::$persistance->restore($this);
			}
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function __destruct()
	{
		if(self::$persistance instanceof IMessagePersistance)
		{
			foreach($this->dataMessage as $typ => $msg)
			{
				self::$persistance->store($typ, $msg);
			}
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	private function __clone()
	{
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected $numer = null;
	protected $opis = null;
	protected $typ = null;
	// -----------------------------------------------------------------------------------------------------------------
	public function getNumer()
	{
		return $this->numer;
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function getOpis()
	{
		return $this->opis;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * @return Message
	 */
	public static function getInstance()
	{
		if(empty(self::$instance))
		{
			self::$instance = new Message();
		}
		return self::$instance;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * @param string $typ
	 * @param self $msg
	 */
	public function save($typ, self $msg)
	{
		$this->dataMessage[$typ][] = $msg;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * @param string $typ
	 * @return Message[]
	 */
	public function getAllByTyp($typ)
	{
		if(isset($this->dataMessage[$typ]))
		{
			$tmp = $this->dataMessage[$typ];
			unset($this->dataMessage[$typ]);
			return $tmp;
		}
		else
		{
			return [];
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public static function import($text)
	{
		$retval = new Message();
		$text = trim($text);
		if($text != "")
		{
			$msgArray = explode(" ", $text, 2);
			$retval->numer = $msgArray[0] ?? -1;
			$retval->opis = $msgArray[1] ?? "Brak szczegółów";
			return $retval;
		}
		else
		{
			return null;
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}
?>