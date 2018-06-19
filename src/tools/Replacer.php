<?php
namespace braga\tools\tools;
/**
 * Created on 16.09.2017 14:39:43
 * @author Tomasz Gajewski
 * package frontoffice
 * error prefix
 */
class Replacer
{
	// -------------------------------------------------------------------------
	protected $message;
	/**
	 *
	 * @var array
	 */
	protected $data;
	// -------------------------------------------------------------------------
	function __construct($message = null)
	{
		$this->message = $message;
		$this->data = array();
	}
	// -------------------------------------------------------------------------
	public function add($search, $replace)
	{
		$this->data[$search] = $replace;
	}
	// -------------------------------------------------------------------------
	public function getFormated()
	{
		$retval = $this->message;
		foreach($this->data as $search => $replace)
		{
			$retval = str_replace($search, $replace, $retval);
		}
		return $retval;
	}
	// -------------------------------------------------------------------------
}