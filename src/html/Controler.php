<?php
namespace braga\tools\html;

use braga\tools\api\BaseRestController;
use braga\tools\exception\BragaException;
use braga\tools\tools\Retval;
use Throwable;
/**
 * @author Tomasz Gajewski
 * @abstract Created on 2009-04-06 10:40:13
 */
abstract class Controler extends BaseRestController
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var callable[]
	 */
	protected array $actions = [];
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var Retval
	 */
	public Retval $r;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var HtmlComponent
	 */
	protected ?HtmlComponent $layOut;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		$this->r = $this->getRetvalObject();
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return Retval
	 */
	abstract protected function getRetvalObject(): Retval;
	// -----------------------------------------------------------------------------------------------------------------
	protected function setLayOut(HtmlComponent $layOut)
	{
		$this->layOut = $layOut;
	}
	// -----------------------------------------------------------------------------------------------------------------
	final protected function page()
	{
		if($this->isXhr())
		{
			$this->sendXml($this->r->getAjax());
		}
		else
		{
			$this->layOut->setContent($this->r->getPage());
			$this->sendHtml($this->layOut->out());
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	final protected function isXhr()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			return $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
		}
		return false;
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function registerSubController(SubController $subController)
	{
		$subController->registerActions();
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param $action
	 * @return void
	 * @throws BragaException|Throwable
	 */
	final public function callAction($action): void
	{
		if(isset($this->actions[$action]))
		{
			$this->actions[$action]();
		}
		else
		{
			throw new BragaException("BT:10401 Akcja " . $action . " nie jest obsługiwana ", 10401);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function registerAction(string $action, callable $fn): void
	{
		if(!isset($this->actions[$action]))
		{
			$this->actions[$action] = $fn;
		}
		else
		{
			throw new BragaException("BT:10402 Akcja " . $action . " jest już zarejestrowana ", 10402);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}
