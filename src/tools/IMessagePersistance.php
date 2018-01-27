<?php
namespace braga\tools\tools;
/**
 * Created on 26 sty 2018 10:02:32
 * error prefix
 * @author Tomasz Gajewski
 * @package
 *
 */
interface IMessagePersistance
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * @param string $typ
	 * @param Message[] $msg
	 */
	public function store(string $typ, array $msg);
	// -----------------------------------------------------------------------------------------------------------------
	public function restore(Message $message);
	// -----------------------------------------------------------------------------------------------------------------
}