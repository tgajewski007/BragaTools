<?php
namespace braga\tools\tools;
/**
 * Created on 26 sty 2018 10:05:53
 * error prefix
 * @author Tomasz Gajewski
 * @package
 *
 */
class MessagePersistanceSession implements IMessagePersistance
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * {@inheritdoc}
	 * @see \braga\tools\tools\IMessagePersistance::store()
	 */
	public function store(string $typ, array $msg)
	{
		SessManager::add($typ, $msg);
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function restore(Message $message)
	{
		if(SessManager::isExist(Message::MESSAGE_ALERT))
		{
			$msg = SessManager::get(Message::MESSAGE_ALERT);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_ALERT, $m);
			}
		}
		if(SessManager::isExist(Message::MESSAGE_INFO))
		{
			$msg = SessManager::get(Message::MESSAGE_INFO);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_INFO, $m);
			}
		}
		if(SessManager::isExist(Message::MESSAGE_SQL))
		{
			$msg = SessManager::get(Message::MESSAGE_SQL);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_SQL, $m);
			}
		}
		if(SessManager::isExist(Message::MESSAGE_WARNING))
		{
			$msg = SessManager::get(Message::MESSAGE_WARNING);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_WARNING, $m);
			}
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}