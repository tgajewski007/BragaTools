<?php
namespace braga\tools\html;

use braga\tools\tools\Retval;
/**
 * @package common
 * @author Tomasz.Gajewski
 * @abstract Created on 2009-04-06 10:40:13
 * klasa odpowiedzialna za pętle komunikatów oraz wstępne sprawdzenie
 * przychodzących danych
 */
abstract class Controler extends BaseControler
{
	// -------------------------------------------------------------------------
	/**
	 * @var HtmlComponent
	 */
	protected $layOut;
	// -------------------------------------------------------------------------
	public function __construct()
	{
		$this->r = $this->getRetvalObject();
	}
	// -------------------------------------------------------------------------
	/**
	 * @return Retval
	 */
	abstract protected function getRetvalObject();
	// -------------------------------------------------------------------------
	protected function setLayOut(HtmlComponent $layOut)
	{
		$this->layOut = $layOut;
	}
	// -------------------------------------------------------------------------
	final protected function page()
	{
		if($this->isXhr())
		{
			if(!headers_sent() && ob_get_length() == 0)
			{
				header("Expires:" . date("D, d M Y H:i:s") . "");
				header("Cache-Control: no-transform; max-age=0; proxy-revalidate ");
				header("Cache-Control: no-cache; must-revalidate; no-store; post-check=0; pre-check=0 ");
				header("Pragma: no-cache");
				header("Content-type: text/xml; charset-utf-8");
				self::sendResponse($this->r->getAjax());
			}
		}
		else
		{
			$this->layOut->setContent($this->r->getPage());
			$this->layOut->out();
		}
	}
	// -------------------------------------------------------------------------
	final protected function isXhr()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			return $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
		}
		return false;
	}
	// -------------------------------------------------------------------------
	public static function sendResponse($response)
	{
		ignore_user_abort(true);
		set_time_limit(0);

		ob_start();
		// do initial processing here
		echo $response; // send the response
		header('Connection: close');
		header('Content-Length: ' . ob_get_length());
		// ob_end_flush();
		ob_flush();
	}
	// -------------------------------------------------------------------------
}
?>